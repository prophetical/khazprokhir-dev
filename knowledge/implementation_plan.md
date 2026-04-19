# High-Precision Serial Replacement Architect — FINAL PLAN

## Goal

Membangun sistem **Range-to-Range Mapping** untuk melacak penggantian seri uang (X Pengganti) pada skala miliaran bilyet, dengan efisiensi penyimpanan maksimal. Termasuk migrasi database dari SQLite ke PostgreSQL.

## Scope

| Phase | Deskripsi |
|-------|-----------|
| **Phase 1** | Migrasi SQLite → PostgreSQL |
| **Phase 2** | Tabel `serial_range_mappings` + Model + Service |
| **Phase 3** | Controller, Routes, Views (Input & Lookup) |

---

## Phase 1: Migrasi SQLite → PostgreSQL

### Langkah-langkah:
1. Install PostgreSQL driver (sudah ada di PHP default)
2. Update `.env` → `DB_CONNECTION=pgsql` + credentials
3. Jalankan `php artisan migrate:fresh` di PostgreSQL baru
4. Export data SQLite → Import ke PostgreSQL via seeder/script

> [!WARNING]
> Migrasi ini akan me-reset database. Jika ada data produksi penting di SQLite,
> perlu di-backup dan di-import manual. Untuk development, `migrate:fresh` cukup.

### Files yang diubah:
- `.env` — ganti connection string

---

## Phase 2: Range-to-Range Mapping Core

### Efisiensi Storage — Angka Nyata

```
TANPA Range Mapping (per bilyet):
  1 batch = 4.500.000 rows
  10 batch = 45.000.000 rows
  Miliaran bilyet = MILIARAN rows → 💀 DB mati

DENGAN Range Mapping:
  1 pack full replaced = 45 rows (1 per prefix/brood)
  1 batch full replaced = 4.500 rows  
  10 batch = 45.000 rows
  Bahkan replace miliaran bilyet = hanya RATUSAN RIBU rows → ⚡ super cepat
```

**Rasio kompresi: ~1000:1 untuk penggantian bulk**

---

### Database Schema

#### [NEW] Migration: `create_serial_range_mappings_table`

```sql
serial_range_mappings
├── id                    BIGSERIAL PRIMARY KEY
├── x_pengganti_seri_id   FK → x_pengganti_seris (batch/seri context)
├── nomor_pack            INTEGER NOT NULL          -- nomor pack asal (701, 702, etc.)
│
│   ── SOURCE (Seri Asal / Rusak) ──
├── source_prefix         CHAR(3) NOT NULL          -- Huruf seri asal: ABA, BBV, etc.
├── source_start          INTEGER NOT NULL           -- Nomor serial awal: 701001
├── source_end            INTEGER NOT NULL           -- Nomor serial akhir: 702000
│
│   ── REPLACEMENT (Seri Pengganti / X) ──
├── replacement_prefix    CHAR(3) NOT NULL           -- Huruf seri pengganti: ZZA, etc.
├── replacement_start     INTEGER NOT NULL           -- Nomor serial awal pengganti
├── replacement_end       INTEGER NOT NULL           -- Nomor serial akhir pengganti
│
│   ── METADATA ──
├── source_category       VARCHAR(20) NOT NULL       -- 'seri_1', 'seri_2', 'campuran_1', 'campuran_2'
├── unit_type             VARCHAR(10) DEFAULT 'brood' -- 'bilyet', 'brood', 'pack'
├── notes                 TEXT NULLABLE
├── created_by            FK → users
├── created_at            TIMESTAMP
└── updated_at            TIMESTAMP

CONSTRAINTS:
  CHECK (source_end >= source_start)
  CHECK (replacement_end >= replacement_start)
  CHECK (source_end - source_start = replacement_end - replacement_start)  -- count match!
  EXCLUDE USING GIST (
    source_prefix WITH =,
    int4range(source_start, source_end, '[]') WITH &&
  ) WHERE (x_pengganti_seri_id IS NOT NULL)   -- no overlapping source ranges per prefix

INDEXES:
  GIST  on (source_prefix, int4range(source_start, source_end, '[]'))     -- range containment query
  BTREE on (replacement_prefix, replacement_start, replacement_end)       -- reverse lookup
  BTREE on (x_pengganti_seri_id, nomor_pack)                             -- filter by batch+pack
  BTREE on (created_at)                                                   -- temporal queries
```

**Mengapa GIST + EXCLUDE:**
- `GIST` index → query `WHERE source_start <= X AND source_end >= X` dalam **O(log n)**
- `EXCLUDE` constraint → database **menolak** insert yang overlap secara otomatis (zero application code needed)
- Perlu extension `btree_gist` (bawaan PostgreSQL, tinggal `CREATE EXTENSION`)

---

### Service Layer: `ReplacementMappingService`

```
┌──────────────────────────────────────────────────────────────────────┐
│                  ReplacementMappingService                          │
├──────────────────────────────────────────────────────────────────────┤
│                                                                      │
│  1. registerBroodReplacement(seriId, pack, srcPrefix,                │
│       srcStart, srcEnd, repPrefix, repStart, repEnd)                 │
│     ─ Validates: count match, no I/X suffix, overlap check           │
│     ─ Creates: 1 row (1 brood = 1.000 bilyet mapped)                │
│     ─ Example: ABA 701001-702000 → ZZA 000001-001000                │
│                                                                      │
│  2. registerPackReplacement(seriId, pack, repSeriLabel)              │
│     ─ Auto-generates: 45 rows (all 45 prefixes of the pack)         │
│     ─ Input: just pack number + replacement seri label               │
│     ─ System auto-calculates all 45 prefix ranges                    │
│     ─ Example: Pack 701 → 45 mapping rows auto-created              │
│                                                                      │
│  3. registerSingleBilyet(seriId, pack, srcPrefix, srcSerial,         │
│       repPrefix, repSerial)                                          │
│     ─ Check: does this serial fall inside existing range?            │
│     ─ YES → SPLIT existing range into 3 parts + insert new          │
│     ─ NO  → Create single-bilyet mapping (start = end)              │
│                                                                      │
│  4. lookupBySourceSerial(prefix, serialNumber)                       │
│     ─ SQL: WHERE source_prefix = ? AND source_start <= ?             │
│            AND source_end >= ?                                       │
│     ─ Offset: serial - source_start                                  │
│     ─ Result: replacement_prefix + (replacement_start + offset)      │
│     ─ Performance: O(log n) via GIST index                           │
│                                                                      │
│  5. reverseLookup(repPrefix, repSerial)                              │
│     ─ Same logic reversed on replacement columns                     │
│     ─ Result: source_prefix + source serial                          │
│                                                                      │
│  6. getMappingsForPack(seriId, packNumber)                           │
│     ─ Returns all mapping rows for a specific pack                   │
│     ─ Paginated, ordered by source_prefix                            │
│                                                                      │
└──────────────────────────────────────────────────────────────────────┘
```

### Range Split Algorithm — Detailed

```
SCENARIO: Brood ABA 701001-702000 already mapped to ZZA 000001-001000.
           Now bilyet ABA 701500 needs individual re-replacement to YYA 000001.

STEP 1: Find existing range containing ABA 701500
         → Found: ABA 701001-702000 → ZZA 000001-001000

STEP 2: Calculate split boundaries
         Left:   ABA 701001-701499 → ZZA 000001-000499  (499 bilyet)
         Single: ABA 701500-701500 → YYA 000001-000001  (1 bilyet, NEW replacement)
         Right:  ABA 701501-702000 → ZZA 000501-001000  (500 bilyet)

STEP 3: In DB transaction:
         DELETE original row
         INSERT left fragment  (if size > 0)
         INSERT single mapping (new replacement)
         INSERT right fragment (if size > 0)

RESULT: 1 row became 3 rows. Total bilyet tracked still = 1000. ✓
```

### Prefix Generation Helper

Auto-generate all 45 prefixes for a given seri label:

```php
// Input: "AB-BB7" → generates:
$seri1Prefixes = ['ABA','ABB','ABC','ABD','ABE','ABF','ABG','ABH',
                  'ABJ','ABK','ABL','ABM','ABN','ABO','ABP','ABQ',
                  'ABR','ABS','ABT','ABU'];  // 20 seri 1

$campuran1Prefixes = ['ABV','ABW','ABY','ABZ'];  // 4 campuran seri 1

$seri2Prefixes = ['BBA','BBB','BBC','BBD','BBE','BBF','BBG','BBH',
                  'BBJ','BBK','BBL','BBM','BBN','BBO','BBP','BBQ',
                  'BBR','BBS','BBT','BBU'];  // 20 seri 2

$campuran2Prefixes = ['BBV'];  // 1 campuran seri 2

// Total: 20 + 4 + 20 + 1 = 45 ✓
```

---

## Phase 3: Controller, Routes & Views

### Routes

```php
Route::prefix('x-pengganti/mapping')->name('x-pengganti.mapping.')->group(function () {
    Route::get('/',          [SerialMappingController::class, 'index'])->name('index');
    Route::get('/create',    [SerialMappingController::class, 'create'])->name('create');
    Route::post('/brood',    [SerialMappingController::class, 'storeBrood'])->name('store.brood');
    Route::post('/pack',     [SerialMappingController::class, 'storePack'])->name('store.pack');
    Route::post('/single',   [SerialMappingController::class, 'storeSingle'])->name('store.single');
    Route::get('/lookup',    [SerialMappingController::class, 'lookup'])->name('lookup');
    Route::post('/lookup',   [SerialMappingController::class, 'doLookup'])->name('lookup.execute');
    Route::delete('/{id}',   [SerialMappingController::class, 'destroy'])->name('destroy');
});
```

### Views

**`create.blade.php` — Form Input dengan 3 Mode:**

```
┌─────────────────────────────────────────────────────┐
│  MODE SELECTOR: [Pack Penuh] [Per Brood] [Per Bilyet] │
├─────────────────────────────────────────────────────┤
│                                                       │
│  MODE: Pack Penuh                                    │
│  ┌──────────────────┬──────────────────────────┐     │
│  │ Seri Asal (auto) │ Seri Pengganti           │     │
│  │ Pack: [701]      │ Prefix Pengganti: [ZZ]   │     │
│  │ (45 brood auto)  │ Start Serial: [000001]   │     │
│  └──────────────────┴──────────────────────────┘     │
│  [Preview 45 rows] [Simpan]                          │
│                                                       │
│  MODE: Per Brood                                     │
│  ┌──────────────────┬──────────────────────────┐     │
│  │ Prefix: [ABA]    │ Rep Prefix: [ZZA]        │     │
│  │ Start:  [701001] │ Rep Start:  [000001]     │     │
│  │ End:    [702000] │ Rep End:    [001000]     │     │
│  └──────────────────┴──────────────────────────┘     │
│  [Simpan]                                            │
│                                                       │
│  MODE: Per Bilyet (triggers range split)             │
│  ┌──────────────────┬──────────────────────────┐     │
│  │ Prefix: [ABA]    │ Rep Prefix: [YYA]        │     │
│  │ Serial: [701500] │ Rep Serial: [000001]     │     │
│  └──────────────────┴──────────────────────────┘     │
│  [Simpan] ⚠️ akan memecah range existing             │
└─────────────────────────────────────────────────────┘
```

**`lookup.blade.php` — Serial Tracing:**

```
┌─────────────────────────────────────────────────────┐
│  CARI SERI PENGGANTI                                │
│  ┌────────────────────────────────────────────┐     │
│  │ Prefix: [ABA] Serial: [701500]            │     │
│  │ [Cari →]  [Reverse Lookup ←]              │     │
│  └────────────────────────────────────────────┘     │
│                                                      │
│  HASIL:                                              │
│  ┌────────────────────────────────────────────┐     │
│  │ Seri Asal:      ABA701500                  │     │
│  │ Seri Pengganti: ZZA000500                  │     │
│  │ Batch: 1322001 | Pack: 701                 │     │
│  │ Kategori: Seri 1                           │     │
│  │ Offset: 500 dari range 701001-702000       │     │
│  └────────────────────────────────────────────┘     │
└─────────────────────────────────────────────────────┘
```

---

## File Summary

| # | Phase | Action | File | Deskripsi |
|---|-------|--------|------|-----------|
| 1 | 1 | MODIFY | `.env` | Ganti `DB_CONNECTION=pgsql` + credentials |
| 2 | 2 | NEW | `database/migrations/..._enable_btree_gist_extension.php` | `CREATE EXTENSION btree_gist` |
| 3 | 2 | NEW | `database/migrations/..._create_serial_range_mappings_table.php` | Tabel inti + GIST + EXCLUDE |
| 4 | 2 | NEW | `app/Models/SerialRangeMapping.php` | Model + scopes + computed attrs |
| 5 | 2 | NEW | `app/Services/ReplacementMappingService.php` | Core engine: register, split, lookup |
| 6 | 2 | NEW | `app/Services/SerialPrefixGenerator.php` | Generate 45 prefixes dari seri label |
| 7 | 2 | NEW | `app/Rules/SerialPrefixRule.php` | Validasi: no I/X ending |
| 8 | 3 | NEW | `app/Http/Controllers/SerialMappingController.php` | HTTP endpoints |
| 9 | 3 | MODIFY | `routes/web.php` | Tambah route group mapping |
| 10 | 3 | NEW | `resources/views/x-pengganti/mapping/index.blade.php` | List mappings |
| 11 | 3 | NEW | `resources/views/x-pengganti/mapping/create.blade.php` | 3-mode input form |
| 12 | 3 | NEW | `resources/views/x-pengganti/mapping/lookup.blade.php` | Lookup + reverse lookup |

---

## Verification Plan

### Automated Tests
```bash
php artisan test --filter=ReplacementMapping
```
- Mass pack replacement → 45 rows created
- Brood replacement → 1 row created
- Single bilyet → range splits correctly into 3
- Lookup returns correct offset-calculated serial
- Reverse lookup works
- Overlap insert rejected by EXCLUDE constraint
- I/X prefix validation rejected
- Count mismatch rejected

### Manual Verification
- Input pack replacement via form → verify 45 rows in DB
- Input single replacement in existing range → verify split
- Lookup serial → verify correct replacement returned
- Check GIST index usage via `EXPLAIN ANALYZE`
