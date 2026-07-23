# Implementasi Role Baru (Tasil) dan Fitur Verifikasi Laporan

Sesuai permintaan Anda, dokumen ini merupakan **Rencana Implementasi** untuk menambahkan role baru bernama `tasil`. Role ini akan bertindak sebagai pengawas (mirip *supervisor*) yang tidak memiliki akses perubahan data (CRUD) di modul operasional, namun secara khusus diberi kewenangan untuk memverifikasi laporan persediaan harian dan rekonsiliasi.

## ⚠️ User Review Required / Open Questions

Mohon tinjau beberapa pertanyaan desain berikut yang akan memengaruhi implementasi teknis:

> [!IMPORTANT]  
> 1. **Cakupan Verifikasi**: Apakah verifikasi dilakukan *per tanggal spesifik* (contoh: verifikasi laporan tanggal 12 Juli)? Untuk rekonsiliasi yang menggunakan *rentang tanggal*, apakah verifikasinya juga memverifikasi rentang tanggal tersebut, atau cukup bergantung pada verifikasi harian?
> 2. **Kunci Data (Data Locking)**: Jika sebuah laporan pada tanggal tertentu sudah di-klik "Verifikasi" oleh role `tasil`, apakah user lain (misalnya staf `kemas` atau `sortir`) menjadi **terkunci** / tidak boleh lagi menambah/mengubah data HCS pada tanggal tersebut?
> 3. **Akses Admin**: Apakah tombol "Verifikasi Laporan" ini *eksklusif* hanya muncul untuk role `tasil`, atau Administrator juga memiliki tombol ini sebagai bentuk akses penuh?

---

## Proposed Changes

Berikut adalah langkah-langkah implementasi teknis yang saya sarankan.

### 1. Database Layer (Tabel Penyimpanan Status Verifikasi)

Kita membutuhkan cara yang *reliable* untuk melacak laporan mana saja yang sudah diverifikasi dan siapa yang memverifikasinya.

#### [NEW] `database/migrations/xxxx_xx_xx_xxxxxx_create_verifikasi_laporans_table.php`
Membuat tabel baru `verifikasi_laporans` dengan skema berikut:
- `id` (Primary Key)
- `jenis_laporan` (enum/string: `'harian'`, `'rekonsiliasi'`)
- `tanggal_mulai` (date) -> Tanggal laporan, atau tanggal awal untuk rekonsiliasi
- `tanggal_akhir` (date) -> Tanggal laporan, atau tanggal akhir untuk rekonsiliasi
- `verified_by` (foreign key ke tabel `users`)
- `verified_at` (timestamp)
- `catatan` (text, opsional, jika `tasil` ingin meninggalkan note)
- `created_at` & `updated_at`

### 2. User Management (Halaman `/users`)

Karena *form* penambahan user di-set secara *hardcode*, kita perlu memperbaruinya agar Admin bisa menugaskan role ini.

#### [MODIFY] `resources/views/users/create.blade.php`
#### [MODIFY] `resources/views/users/edit.blade.php`
Menambahkan opsi role `tasil` pada grid pilihan role:
```diff
- @foreach(['admin' => 'Administrator', 'supervisor' => 'Supervisor', 'sortir' => 'Staff Sortir', 'kemas' => 'Staff Pengemasan', 'khazverutas' => 'Staff Khazverutas'] as $val => $label)
+ @foreach(['admin' => 'Administrator', 'supervisor' => 'Supervisor', 'tasil' => 'Staff Tasil (Verifikator)', 'sortir' => 'Staff Sortir', 'kemas' => 'Staff Pengemasan', 'khazverutas' => 'Staff Khazverutas'] as $val => $label)
```

### 3. Middleware & Keamanan (`RoleMiddleware`)

Hak akses aplikasi dikelola oleh `RoleMiddleware`. Kita perlu menambahkan logika khusus untuk `tasil`.

#### [MODIFY] `app/Http/Middleware/RoleMiddleware.php`
Menambahkan pemeriksaan role `tasil` yang serupa dengan `supervisor` (hanya `GET`), **tetapi** dengan pengecualian (*whitelist*) untuk route pengiriman (POST) verifikasi laporan.

### 4. Routing & Controller Logic

Menambahkan titik akhir (endpoint) yang menangani pengiriman status verifikasi dari role `tasil`.

#### [MODIFY] `routes/web.php`
```php
// Tambahkan pada group route laporan harian
Route::post('/laporan-harian/verifikasi', [LaporanHarianController::class, 'verifikasiHarian'])->name('laporan-harian.verifikasi');
Route::post('/laporan-harian/rekonsiliasi/verifikasi', [LaporanHarianController::class, 'verifikasiRekonsiliasi'])->name('laporan-harian.rekonsiliasi-verifikasi');
```

#### [MODIFY] `app/Http/Controllers/LaporanHarianController.php`
- Tambahkan metode `verifikasiHarian()` dan `verifikasiRekonsiliasi()` yang akan menerima *request* verifikasi, dan mem-validasi/menyimpan datanya ke tabel `verifikasi_laporans`.
- Di dalam metode `index()` dan `rekonsiliasi()` yang sudah ada, ambil status verifikasinya dari database (apakah untuk tanggal tersebut sudah ada di `verifikasi_laporans`) dan kirim variabel `$statusVerifikasi` ke *view* blade.

### 5. UI Laporan Harian & Rekonsiliasi

Modifikasi tampilan agar informatif bagi manajemen dan interaktif bagi role `tasil`.

#### [MODIFY] `resources/views/laporan-harian/index.blade.php`
#### [MODIFY] `resources/views/laporan-harian/rekonsiliasi.blade.php`
- **Banner/Badge Status**: Memunculkan elemen UI di bagian atas (contoh: *Badge* hijau "✅ Telah diverifikasi oleh [Nama] pada [Waktu]" jika sudah diverifikasi, atau *Badge* kuning "⚠️ Belum diverifikasi").
- **Tombol Aksi**: Sebuah form *submit* dengan tombol **"✅ Verifikasi Laporan Ini"**. Tombol ini dibungkus dalam blok `@if(auth()->user()->role === 'tasil' && !$sudahDiverifikasi)`.

---

## Verification Plan

1. **Test User Management**: Login sebagai admin, pastikan role `tasil` muncul di form tambah/edit user, dan berhasil disimpan ke database.
2. **Test Hak Akses (Authorization)**: Login sebagai user `tasil`. Mencoba membuat atau menghapus data pengemasan/penerimaan secara langsung melalui URL/tombol (harus tertolak oleh 403 Forbidden).
3. **Test Flow Verifikasi**:
   - Buka `/laporan-harian`. Pastikan ada peringatan "Belum diverifikasi".
   - Klik tombol verifikasi. Halaman di-refresh, dan muncul "Telah diverifikasi oleh [Nama User]".
   - Buka `/laporan-harian/rekonsiliasi` dengan filter bulan tertentu, lakukan tes verifikasi yang sama.

Silakan tinjau **Pertanyaan Desain** di atas, dan klik **Proceed** jika Anda menyetujui rencana implementasi ini.
