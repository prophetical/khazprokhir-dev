# Domain Knowledge: Seri, Serial Number & Pengemasan — KHAZPRO System

## Keputusan yang Sudah Diambil

1. **Database Engine:** ✅ Migrasi ke PostgreSQL (termasuk migrasi data existing)
2. **Integrasi Modul:** ✅ Opsi B — Terintegrasi dengan Khazai, Cutpack, Rikyet
3. **Input Method:** Range-based input (seri pengganti seringkali berurutan saat production)

---

## 1. Hirarki Unit

| Unit | Isi | Keterangan |
|------|-----|------------|
| 1 bilyet | 1 lembar uang kertas | Unit terkecil |
| 1 brood | 1.000 bilyet | 1 prefix huruf seri × 1.000 nomor |
| 1 vell | 45 bilyet | 1 lembar besar belum dipotong |
| 1 pack | 45 brood = 45.000 bilyet | 45 prefix huruf seri × 1.000 nomor |
| 1 dus | 20.000 bilyet | Unit pengemasan (**!) |
| 1 batch | 100 pack = 4.500.000 bilyet | |

> **CATATAN:** User menulis "1 dus = 2000 bilyet" di unit list, tapi tabel pengemasan
> menunjukkan setiap dus = 20.000 bilyet. Kemungkinan typo (harusnya 20.000).
> Verifikasi: 4 pack × 45.000 = 180.000 ÷ 9 dus = 20.000 ✓

---

## 2. Sistem Seri (Serial Format)

### 2.1 Format Label Seri: `XX-XX9`

Contoh: `AB-BB7`

```
AB - BB 7
│    │  └── Digit penunjuk nomor pack: 7 → pack 701-800
│    └───── 2 huruf pertama prefix Seri 2: BB → BBA,BBB,...,BBU,BBV
└────────── 2 huruf pertama prefix Seri 1: AB → ABA,ABB,...,ABU,ABV,ABW,ABY,ABZ
```

### 2.2 Mapping Digit → Nomor Pack

| Digit | Nomor Pack | Jumlah |
|-------|------------|--------|
| 0 | 001 – 100 | 100 pack |
| 1 | 101 – 200 | 100 pack |
| 2 | 201 – 300 | 100 pack |
| 3 | 301 – 400 | 100 pack |
| 4 | 401 – 500 | 100 pack |
| 5 | 501 – 600 | 100 pack |
| 6 | 601 – 700 | 100 pack |
| 7 | 701 – 800 | 100 pack |
| 8 | 801 – 900 | 100 pack |
| 9 | 901 – 1000 | 100 pack |

### 2.3 Serial Number Per Bilyet — Format Lengkap

**Format: `[3 huruf prefix][6 digit nomor]`**

Contoh: `ABA701001`

```
ABA 701001
│││ └───── 6 digit nomor serial
│││         Derived from: (pack_number × 1000) + (1 to 1000)
│││         Pack 701 → 701001 to 702000
│││         Pack 702 → 702001 to 703000
│└┘─────── Huruf ke-3: rotasi A-Z (skip I dan X)
└──────── Huruf ke-1 & ke-2: dari label seri (AB untuk Seri 1, BB untuk Seri 2)
```

### 2.4 Huruf Seri ke-3 — Rotasi Lengkap

**Per 1 Pack = 45 Brood = 45 Prefix:**

#### Seri 1 (20 brood):
| No | Prefix | Keterangan |
|----|--------|------------|
| 1 | AB**A** | Seri 1 |
| 2 | AB**B** | Seri 1 |
| 3 | AB**C** | Seri 1 |
| 4 | AB**D** | Seri 1 |
| 5 | AB**E** | Seri 1 |
| 6 | AB**F** | Seri 1 |
| 7 | AB**G** | Seri 1 |
| 8 | AB**H** | Seri 1 |
| 9 | AB**J** | Seri 1 (skip I) |
| 10 | AB**K** | Seri 1 |
| 11 | AB**L** | Seri 1 |
| 12 | AB**M** | Seri 1 |
| 13 | AB**N** | Seri 1 |
| 14 | AB**O** | Seri 1 |
| 15 | AB**P** | Seri 1 |
| 16 | AB**Q** | Seri 1 |
| 17 | AB**R** | Seri 1 |
| 18 | AB**S** | Seri 1 |
| 19 | AB**T** | Seri 1 |
| 20 | AB**U** | Seri 1 |

#### Campuran Seri 1 (4 brood):
| No | Prefix | Keterangan |
|----|--------|------------|
| 21 | AB**V** | Campuran (skip X) |
| 22 | AB**W** | Campuran |
| 23 | AB**Y** | Campuran (skip X) |
| 24 | AB**Z** | Campuran |

#### Seri 2 (20 brood):
| No | Prefix | Keterangan |
|----|--------|------------|
| 25 | BB**A** | Seri 2 |
| 26 | BB**B** | Seri 2 |
| ... | ... | ... |
| 44 | BB**U** | Seri 2 |

#### Campuran Seri 2 (1 brood):
| No | Prefix | Keterangan |
|----|--------|------------|
| 45 | BB**V** | Campuran Seri 2 |

**Total: 20 + 4 + 20 + 1 = 45 brood = 45.000 bilyet ✓**

### 2.5 Huruf Valid untuk Posisi ke-3

```
Normal:   A B C D E F G H J K L M N O P Q R S T U  (20 huruf, skip I)
Campuran: V W Y Z                                    (4 huruf, skip X)
Total:    24 huruf valid
```

---

## 3. Sistem Pengemasan (Dus)

### 3.1 Aturan: 4 Pack Berurutan → 9 Dus

Setiap 4 pack yang berurutan (kelipatan 4) dikemas menjadi 9 dus.

**Contoh: Pack 101-104**

| Dus # | Isi | Bilyet |
|-------|-----|--------|
| 1 | **Campuran** Pack 101-104 gabungan | 20.000 |
| 2 | Seri 1 Pack 101 | 20.000 |
| 3 | Seri 2 Pack 101 | 20.000 |
| 4 | Seri 1 Pack 102 | 20.000 |
| 5 | Seri 2 Pack 102 | 20.000 |
| 6 | Seri 1 Pack 103 | 20.000 |
| 7 | Seri 2 Pack 103 | 20.000 |
| 8 | Seri 1 Pack 104 | 20.000 |
| 9 | Seri 2 Pack 104 | 20.000 |

**Total: 9 × 20.000 = 180.000 = 4 × 45.000 ✓**

### 3.2 Verifikasi Isi Per Dus

**Dus 1 (Campuran 4 pack):**
- Per pack campuran: 4 brood Seri 1 (V,W,Y,Z) + 1 brood Seri 2 (V) = 5 brood = 5.000 bilyet
- 4 pack × 5.000 = 20.000 ✓

**Dus 2 (Seri 1 Pack 101):**
- 20 brood Seri 1 × 1.000 = 20.000 ✓

**Dus 3 (Seri 2 Pack 101):**
- 20 brood Seri 2 × 1.000 = 20.000 ✓

### 3.3 Nomor Dus
- Nomor dus selalu bertambah (sequential) per kombinasi: pecahan + tahun emisi + tahun anggaran

---

## 4. Contoh Batch Lengkap

### Contoh 1: Batch 1322001, Seri AA-BA0, TA 2026, TE 2022
- Pack: 001 – 100
- Seri 1 prefix: AA* (AAA, AAB, ..., AAU, AAV, AAW, AAY, AAZ)
- Seri 2 prefix: BA* (BAA, BAB, ..., BAU, BAV)
- Pack 001 serial range: 001001 – 002000
- Pack 100 serial range: 100001 – 101000

### Contoh 2: Batch 1822102, Seri NA-OA3, TA 2026, TE 2022
- Pack: 301 – 400
- Seri 1 prefix: NA* (NAA, NAB, ..., NAU, NAV, NAW, NAY, NAZ)
- Seri 2 prefix: OA* (OAA, OAB, ..., OAU, OAV)
- Pack 301 serial range: 301001 – 302000
- Pack 400 serial range: 400001 – 401000

---

## 5. Implikasi untuk Range-to-Range Mapping

### Skenario Penggantian:

**Kasus 1: Ganti 1 brood penuh**
- Source: ABA 701001-702000 (1000 bilyet)
- Replacement: ZZZ 000001-001000 (1000 bilyet)
- → 1 row di mapping table

**Kasus 2: Ganti 1 pack penuh (semua 45 brood)**
- 45 rows di mapping table (1 per prefix)
- Atau jika replacement serial juga berurutan secara prefix, bisa dioptimasi

**Kasus 3: Ganti 1 bilyet tunggal di tengah brood**
- Split brood range menjadi 3 bagian
- ABA 701001-701499, ABA 701500 (single), ABA 701501-702000

### Key Insight:
Mapping selalu terjadi pada level **1 prefix** (3 huruf seri).
Source dan replacement selalu punya prefix yang sama atau berbeda,
tapi serial number range harus 1:1 match.
