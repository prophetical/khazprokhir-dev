<?php

// @formatter:off
// phpcs:ignoreFile
/**
 * A helper file for your Eloquent Models
 * Copy the phpDocs from this file to the correct Model,
 * And remove them from this file, to prevent double declarations.
 *
 * @author Barry vd. Heuvel <barryvdh@gmail.com>
 */


namespace App\Models{
/**
 * @property int $id
 * @property int|null $user_id
 * @property string $action
 * @property string $module
 * @property string|null $record_id
 * @property \Illuminate\Support\Carbon|null $created_at
 * @property \Illuminate\Support\Carbon|null $updated_at
 * @property-read \App\Models\User|null $user
 * @method static \Illuminate\Database\Eloquent\Builder<static>|AuditLog newModelQuery()
 * @method static \Illuminate\Database\Eloquent\Builder<static>|AuditLog newQuery()
 * @method static \Illuminate\Database\Eloquent\Builder<static>|AuditLog query()
 * @method static \Illuminate\Database\Eloquent\Builder<static>|AuditLog whereAction($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|AuditLog whereCreatedAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|AuditLog whereId($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|AuditLog whereModule($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|AuditLog whereRecordId($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|AuditLog whereUpdatedAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|AuditLog whereUserId($value)
 */
	class AuditLog extends \Eloquent {}
}

namespace App\Models{
/**
 * @property int $id
 * @property int $id_pengemasan
 * @property int $no_dus
 * @property int|null $pack_awal
 * @property int|null $pack_akhir
 * @property string $seri_awal
 * @property string $seri_akhir
 * @property string $batch
 * @property int $jumlah_bilyet
 * @property \Illuminate\Support\Carbon|null $created_at
 * @property \Illuminate\Support\Carbon|null $updated_at
 * @property-read \App\Models\Pengemasan $pengemasan
 * @method static \Illuminate\Database\Eloquent\Builder<static>|DetailPengemasan newModelQuery()
 * @method static \Illuminate\Database\Eloquent\Builder<static>|DetailPengemasan newQuery()
 * @method static \Illuminate\Database\Eloquent\Builder<static>|DetailPengemasan query()
 * @method static \Illuminate\Database\Eloquent\Builder<static>|DetailPengemasan whereBatch($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|DetailPengemasan whereCreatedAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|DetailPengemasan whereId($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|DetailPengemasan whereIdPengemasan($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|DetailPengemasan whereJumlahBilyet($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|DetailPengemasan whereNoDus($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|DetailPengemasan wherePackAkhir($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|DetailPengemasan wherePackAwal($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|DetailPengemasan whereSeriAkhir($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|DetailPengemasan whereSeriAwal($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|DetailPengemasan whereUpdatedAt($value)
 */
	class DetailPengemasan extends \Eloquent {}
}

namespace App\Models{
/**
 * @property int $id
 * @property string $nomor_bon
 * @property string $tanggal_penerimaan
 * @property string $pecahan
 * @property int $jumlah
 * @property string $gilir
 * @property string $mesin
 * @property string $supplier
 * @property string $batch
 * @property string $seri
 * @property int $emisi
 * @property string|null $repass
 * @property int $created_by
 * @property \Illuminate\Support\Carbon|null $created_at
 * @property \Illuminate\Support\Carbon|null $updated_at
 * @property string $tahun_anggaran
 * @property-read \Illuminate\Database\Eloquent\Collection<int, \App\Models\Pack> $packs
 * @property-read int|null $packs_count
 * @property-read \App\Models\User $user
 * @method static \Illuminate\Database\Eloquent\Builder<static>|HcsReceiving newModelQuery()
 * @method static \Illuminate\Database\Eloquent\Builder<static>|HcsReceiving newQuery()
 * @method static \Illuminate\Database\Eloquent\Builder<static>|HcsReceiving query()
 * @method static \Illuminate\Database\Eloquent\Builder<static>|HcsReceiving whereBatch($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|HcsReceiving whereCreatedAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|HcsReceiving whereCreatedBy($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|HcsReceiving whereEmisi($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|HcsReceiving whereGilir($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|HcsReceiving whereId($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|HcsReceiving whereJumlah($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|HcsReceiving whereMesin($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|HcsReceiving whereNomorBon($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|HcsReceiving wherePecahan($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|HcsReceiving whereRepass($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|HcsReceiving whereSeri($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|HcsReceiving whereSupplier($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|HcsReceiving whereTahunAnggaran($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|HcsReceiving whereTanggalPenerimaan($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|HcsReceiving whereUpdatedAt($value)
 */
	class HcsReceiving extends \Eloquent {}
}

namespace App\Models{
/**
 * @property int $id
 * @property string $pecahan
 * @property string $batch
 * @property string $seri
 * @property string $supplier
 * @property array<array-key, mixed> $packs_selected
 * @property int $jumlah_pack
 * @property int $jumlah_bilyet
 * @property string $petugas_1
 * @property string|null $petugas_2
 * @property \Illuminate\Support\Carbon $tanggal_sortir
 * @property string $gilir
 * @property int $created_by
 * @property \Illuminate\Support\Carbon|null $created_at
 * @property \Illuminate\Support\Carbon|null $updated_at
 * @property int|null $emisi
 * @property string $tahun_anggaran
 * @property int $status_kunci_pengemasan
 * @property-read \Illuminate\Database\Eloquent\Collection<int, \App\Models\Pack> $packs
 * @property-read int|null $packs_count
 * @property-read \App\Models\User $user
 * @method static \Illuminate\Database\Eloquent\Builder<static>|HcsSorting newModelQuery()
 * @method static \Illuminate\Database\Eloquent\Builder<static>|HcsSorting newQuery()
 * @method static \Illuminate\Database\Eloquent\Builder<static>|HcsSorting query()
 * @method static \Illuminate\Database\Eloquent\Builder<static>|HcsSorting whereBatch($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|HcsSorting whereCreatedAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|HcsSorting whereCreatedBy($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|HcsSorting whereEmisi($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|HcsSorting whereGilir($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|HcsSorting whereId($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|HcsSorting whereJumlahBilyet($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|HcsSorting whereJumlahPack($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|HcsSorting wherePacksSelected($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|HcsSorting wherePecahan($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|HcsSorting wherePetugas1($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|HcsSorting wherePetugas2($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|HcsSorting whereSeri($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|HcsSorting whereStatusKunciPengemasan($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|HcsSorting whereSupplier($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|HcsSorting whereTahunAnggaran($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|HcsSorting whereTanggalSortir($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|HcsSorting whereUpdatedAt($value)
 */
	class HcsSorting extends \Eloquent {}
}

namespace App\Models{
/**
 * @property int $id
 * @property string $nomor_bon
 * @property string $tanggal_penerimaan
 * @property string $pecahan
 * @property int $jumlah
 * @property string $batch
 * @property string $seri
 * @property int $emisi
 * @property int $tahun_anggaran
 * @property string $nomor_segel
 * @property int $created_by
 * @property \Illuminate\Support\Carbon|null $created_at
 * @property \Illuminate\Support\Carbon|null $updated_at
 * @property string|null $gilir
 * @property-read \App\Models\User $user
 * @method static \Illuminate\Database\Eloquent\Builder<static>|HctsReceiving newModelQuery()
 * @method static \Illuminate\Database\Eloquent\Builder<static>|HctsReceiving newQuery()
 * @method static \Illuminate\Database\Eloquent\Builder<static>|HctsReceiving query()
 * @method static \Illuminate\Database\Eloquent\Builder<static>|HctsReceiving whereBatch($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|HctsReceiving whereCreatedAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|HctsReceiving whereCreatedBy($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|HctsReceiving whereEmisi($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|HctsReceiving whereGilir($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|HctsReceiving whereId($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|HctsReceiving whereJumlah($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|HctsReceiving whereNomorBon($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|HctsReceiving whereNomorSegel($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|HctsReceiving wherePecahan($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|HctsReceiving whereSeri($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|HctsReceiving whereTahunAnggaran($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|HctsReceiving whereTanggalPenerimaan($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|HctsReceiving whereUpdatedAt($value)
 */
	class HctsReceiving extends \Eloquent {}
}

namespace App\Models{
/**
 * @property int $id
 * @property string $tanggal_penyerahan
 * @property string $pecahan
 * @property int $tahun_anggaran
 * @property int $tahun_emisi
 * @property int $jumlah_bilyet
 * @property string $pemasok1
 * @property string|null $pemasok2
 * @property string $nomor_ba
 * @property int $created_by
 * @property \Illuminate\Support\Carbon|null $created_at
 * @property \Illuminate\Support\Carbon|null $updated_at
 * @property-read \Illuminate\Database\Eloquent\Collection<int, \App\Models\HctsSubmissionBatch> $batches
 * @property-read int|null $batches_count
 * @property-read \App\Models\User $user
 * @method static \Illuminate\Database\Eloquent\Builder<static>|HctsSubmission newModelQuery()
 * @method static \Illuminate\Database\Eloquent\Builder<static>|HctsSubmission newQuery()
 * @method static \Illuminate\Database\Eloquent\Builder<static>|HctsSubmission query()
 * @method static \Illuminate\Database\Eloquent\Builder<static>|HctsSubmission whereCreatedAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|HctsSubmission whereCreatedBy($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|HctsSubmission whereId($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|HctsSubmission whereJumlahBilyet($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|HctsSubmission whereNomorBa($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|HctsSubmission wherePecahan($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|HctsSubmission wherePemasok1($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|HctsSubmission wherePemasok2($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|HctsSubmission whereTahunAnggaran($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|HctsSubmission whereTahunEmisi($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|HctsSubmission whereTanggalPenyerahan($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|HctsSubmission whereUpdatedAt($value)
 */
	class HctsSubmission extends \Eloquent {}
}

namespace App\Models{
/**
 * @property int $id
 * @property int $hcts_submission_id
 * @property string $batch
 * @property int $jumlah
 * @property \Illuminate\Support\Carbon|null $created_at
 * @property \Illuminate\Support\Carbon|null $updated_at
 * @property-read \App\Models\HctsSubmission $submission
 * @method static \Illuminate\Database\Eloquent\Builder<static>|HctsSubmissionBatch newModelQuery()
 * @method static \Illuminate\Database\Eloquent\Builder<static>|HctsSubmissionBatch newQuery()
 * @method static \Illuminate\Database\Eloquent\Builder<static>|HctsSubmissionBatch query()
 * @method static \Illuminate\Database\Eloquent\Builder<static>|HctsSubmissionBatch whereBatch($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|HctsSubmissionBatch whereCreatedAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|HctsSubmissionBatch whereHctsSubmissionId($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|HctsSubmissionBatch whereId($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|HctsSubmissionBatch whereJumlah($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|HctsSubmissionBatch whereUpdatedAt($value)
 */
	class HctsSubmissionBatch extends \Eloquent {}
}

namespace App\Models{
/**
 * @property int $id
 * @property int $hcs_receiving_id
 * @property string $batch
 * @property string $seri
 * @property int $pack_number
 * @property string $supplier
 * @property int $created_by
 * @property \Illuminate\Support\Carbon|null $created_at
 * @property \Illuminate\Support\Carbon|null $updated_at
 * @property int|null $hcs_sorting_id
 * @property int|null $pengemasan_id
 * @property int|null $id_pengemasan
 * @property int $jumlah
 * @property-read \App\Models\HcsReceiving $hcsReceiving
 * @property-read \App\Models\HcsSorting|null $hcsSorting
 * @property-read \App\Models\Pengemasan|null $pengemasan
 * @property-read \App\Models\User $user
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Pack newModelQuery()
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Pack newQuery()
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Pack query()
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Pack whereBatch($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Pack whereCreatedAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Pack whereCreatedBy($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Pack whereHcsReceivingId($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Pack whereHcsSortingId($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Pack whereId($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Pack whereIdPengemasan($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Pack whereJumlah($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Pack wherePackNumber($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Pack wherePengemasanId($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Pack whereSeri($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Pack whereSupplier($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Pack whereUpdatedAt($value)
 */
	class Pack extends \Eloquent {}
}

namespace App\Models{
/**
 * @property int $id
 * @property \Illuminate\Support\Carbon $tanggal_pengemasan
 * @property string $gilir
 * @property string $tahun_anggaran
 * @property int $tahun_emisi
 * @property string $pecahan
 * @property string $batch
 * @property string $seri
 * @property int $pack_awal
 * @property int $pack_akhir
 * @property int $jumlah_pack
 * @property int $jumlah_dus
 * @property int $dus_awal
 * @property int $dus_akhir
 * @property int $created_by
 * @property \Illuminate\Support\Carbon|null $created_at
 * @property \Illuminate\Support\Carbon|null $updated_at
 * @property int $total_bilyet
 * @property-read \Illuminate\Database\Eloquent\Collection<int, \App\Models\DetailPengemasan> $detailPengemasans
 * @property-read int|null $detail_pengemasans_count
 * @property-read \Illuminate\Database\Eloquent\Collection<int, \App\Models\Pack> $packs
 * @property-read int|null $packs_count
 * @property-read \App\Models\User $user
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Pengemasan newModelQuery()
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Pengemasan newQuery()
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Pengemasan query()
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Pengemasan whereBatch($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Pengemasan whereCreatedAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Pengemasan whereCreatedBy($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Pengemasan whereDusAkhir($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Pengemasan whereDusAwal($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Pengemasan whereGilir($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Pengemasan whereId($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Pengemasan whereJumlahDus($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Pengemasan whereJumlahPack($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Pengemasan wherePackAkhir($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Pengemasan wherePackAwal($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Pengemasan wherePecahan($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Pengemasan whereSeri($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Pengemasan whereTahunAnggaran($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Pengemasan whereTahunEmisi($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Pengemasan whereTanggalPengemasan($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Pengemasan whereTotalBilyet($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Pengemasan whereUpdatedAt($value)
 */
	class Pengemasan extends \Eloquent {}
}

namespace App\Models{
/**
 * @property int $id
 * @property \Illuminate\Support\Carbon $tanggal_penyerahan
 * @property string $nomor_ba
 * @property string $pecahan
 * @property int $tahun_emisi
 * @property string $tahun_anggaran
 * @property int $nomor_dus_awal
 * @property int $nomor_dus_akhir
 * @property int $jumlah_dus
 * @property int $jumlah_bilyet
 * @property string $status_data
 * @property int $created_by
 * @property \Illuminate\Support\Carbon|null $created_at
 * @property \Illuminate\Support\Carbon|null $updated_at
 * @property-read \App\Models\User $user
 * @method static \Illuminate\Database\Eloquent\Builder<static>|PenyerahanBi newModelQuery()
 * @method static \Illuminate\Database\Eloquent\Builder<static>|PenyerahanBi newQuery()
 * @method static \Illuminate\Database\Eloquent\Builder<static>|PenyerahanBi query()
 * @method static \Illuminate\Database\Eloquent\Builder<static>|PenyerahanBi whereCreatedAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|PenyerahanBi whereCreatedBy($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|PenyerahanBi whereId($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|PenyerahanBi whereJumlahBilyet($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|PenyerahanBi whereJumlahDus($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|PenyerahanBi whereNomorBa($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|PenyerahanBi whereNomorDusAkhir($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|PenyerahanBi whereNomorDusAwal($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|PenyerahanBi wherePecahan($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|PenyerahanBi whereStatusData($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|PenyerahanBi whereTahunAnggaran($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|PenyerahanBi whereTahunEmisi($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|PenyerahanBi whereTanggalPenyerahan($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|PenyerahanBi whereUpdatedAt($value)
 */
	class PenyerahanBi extends \Eloquent {}
}

namespace App\Models{
/**
 * @property int $id
 * @property string $pecahan
 * @property string $batch
 * @property string $seri
 * @property int $total_received
 * @property int $total_packed
 * @property int $total_delivered
 * @property \Illuminate\Support\Carbon|null $created_at
 * @property \Illuminate\Support\Carbon|null $updated_at
 * @method static \Illuminate\Database\Eloquent\Builder<static>|StockLedger newModelQuery()
 * @method static \Illuminate\Database\Eloquent\Builder<static>|StockLedger newQuery()
 * @method static \Illuminate\Database\Eloquent\Builder<static>|StockLedger query()
 * @method static \Illuminate\Database\Eloquent\Builder<static>|StockLedger whereBatch($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|StockLedger whereCreatedAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|StockLedger whereId($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|StockLedger wherePecahan($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|StockLedger whereSeri($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|StockLedger whereTotalDelivered($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|StockLedger whereTotalPacked($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|StockLedger whereTotalReceived($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|StockLedger whereUpdatedAt($value)
 */
	class StockLedger extends \Eloquent {}
}

namespace App\Models{
/**
 * @property int $id
 * @property string $pecahan
 * @property int $tahun_anggaran
 * @property int $tahun_emisi
 * @property numeric $bulan_1
 * @property numeric $bulan_2
 * @property numeric $bulan_3
 * @property numeric $bulan_4
 * @property numeric $bulan_5
 * @property numeric $bulan_6
 * @property numeric $bulan_7
 * @property numeric $bulan_8
 * @property numeric $bulan_9
 * @property numeric $bulan_10
 * @property numeric $bulan_11
 * @property numeric $bulan_12
 * @property \Illuminate\Support\Carbon|null $created_at
 * @property \Illuminate\Support\Carbon|null $updated_at
 * @method static \Illuminate\Database\Eloquent\Builder<static>|TargetBulanan newModelQuery()
 * @method static \Illuminate\Database\Eloquent\Builder<static>|TargetBulanan newQuery()
 * @method static \Illuminate\Database\Eloquent\Builder<static>|TargetBulanan query()
 * @method static \Illuminate\Database\Eloquent\Builder<static>|TargetBulanan whereBulan1($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|TargetBulanan whereBulan10($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|TargetBulanan whereBulan11($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|TargetBulanan whereBulan12($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|TargetBulanan whereBulan2($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|TargetBulanan whereBulan3($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|TargetBulanan whereBulan4($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|TargetBulanan whereBulan5($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|TargetBulanan whereBulan6($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|TargetBulanan whereBulan7($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|TargetBulanan whereBulan8($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|TargetBulanan whereBulan9($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|TargetBulanan whereCreatedAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|TargetBulanan whereId($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|TargetBulanan wherePecahan($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|TargetBulanan whereTahunAnggaran($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|TargetBulanan whereTahunEmisi($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|TargetBulanan whereUpdatedAt($value)
 */
	class TargetBulanan extends \Eloquent {}
}

namespace App\Models{
/**
 * @property int $id
 * @property string $pecahan
 * @property int $tahun_anggaran
 * @property int $tahun_emisi
 * @property numeric $bulan_1
 * @property numeric $bulan_2
 * @property numeric $bulan_3
 * @property numeric $bulan_4
 * @property numeric $bulan_5
 * @property numeric $bulan_6
 * @property numeric $bulan_7
 * @property numeric $bulan_8
 * @property numeric $bulan_9
 * @property numeric $bulan_10
 * @property numeric $bulan_11
 * @property numeric $bulan_12
 * @property \Illuminate\Support\Carbon|null $created_at
 * @property \Illuminate\Support\Carbon|null $updated_at
 * @method static \Illuminate\Database\Eloquent\Builder<static>|TargetBulananPengemasan newModelQuery()
 * @method static \Illuminate\Database\Eloquent\Builder<static>|TargetBulananPengemasan newQuery()
 * @method static \Illuminate\Database\Eloquent\Builder<static>|TargetBulananPengemasan query()
 * @method static \Illuminate\Database\Eloquent\Builder<static>|TargetBulananPengemasan whereBulan1($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|TargetBulananPengemasan whereBulan10($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|TargetBulananPengemasan whereBulan11($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|TargetBulananPengemasan whereBulan12($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|TargetBulananPengemasan whereBulan2($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|TargetBulananPengemasan whereBulan3($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|TargetBulananPengemasan whereBulan4($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|TargetBulananPengemasan whereBulan5($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|TargetBulananPengemasan whereBulan6($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|TargetBulananPengemasan whereBulan7($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|TargetBulananPengemasan whereBulan8($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|TargetBulananPengemasan whereBulan9($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|TargetBulananPengemasan whereCreatedAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|TargetBulananPengemasan whereId($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|TargetBulananPengemasan wherePecahan($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|TargetBulananPengemasan whereTahunAnggaran($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|TargetBulananPengemasan whereTahunEmisi($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|TargetBulananPengemasan whereUpdatedAt($value)
 */
	class TargetBulananPengemasan extends \Eloquent {}
}

namespace App\Models{
/**
 * @property int $id
 * @property string $pecahan
 * @property int $tahun_anggaran
 * @property int $tahun_emisi
 * @property numeric $target
 * @property \Illuminate\Support\Carbon|null $created_at
 * @property \Illuminate\Support\Carbon|null $updated_at
 * @method static \Illuminate\Database\Eloquent\Builder<static>|TargetTahunan newModelQuery()
 * @method static \Illuminate\Database\Eloquent\Builder<static>|TargetTahunan newQuery()
 * @method static \Illuminate\Database\Eloquent\Builder<static>|TargetTahunan query()
 * @method static \Illuminate\Database\Eloquent\Builder<static>|TargetTahunan whereCreatedAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|TargetTahunan whereId($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|TargetTahunan wherePecahan($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|TargetTahunan whereTahunAnggaran($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|TargetTahunan whereTahunEmisi($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|TargetTahunan whereTarget($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|TargetTahunan whereUpdatedAt($value)
 */
	class TargetTahunan extends \Eloquent {}
}

namespace App\Models{
/**
 * @property int $id
 * @property string $name
 * @property string $email
 * @property \Illuminate\Support\Carbon|null $email_verified_at
 * @property string $password
 * @property string $role
 * @property string|null $remember_token
 * @property \Illuminate\Support\Carbon|null $created_at
 * @property \Illuminate\Support\Carbon|null $updated_at
 * @property string|null $permissions
 * @property-read \Illuminate\Database\Eloquent\Collection<int, \App\Models\AuditLog> $auditLogs
 * @property-read int|null $audit_logs_count
 * @property-read \Illuminate\Database\Eloquent\Collection<int, \App\Models\HcsReceiving> $hcsReceivings
 * @property-read int|null $hcs_receivings_count
 * @property-read \Illuminate\Notifications\DatabaseNotificationCollection<int, \Illuminate\Notifications\DatabaseNotification> $notifications
 * @property-read int|null $notifications_count
 * @method static \Database\Factories\UserFactory factory($count = null, $state = [])
 * @method static \Illuminate\Database\Eloquent\Builder<static>|User newModelQuery()
 * @method static \Illuminate\Database\Eloquent\Builder<static>|User newQuery()
 * @method static \Illuminate\Database\Eloquent\Builder<static>|User query()
 * @method static \Illuminate\Database\Eloquent\Builder<static>|User whereCreatedAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|User whereEmail($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|User whereEmailVerifiedAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|User whereId($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|User whereName($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|User wherePassword($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|User wherePermissions($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|User whereRememberToken($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|User whereRole($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|User whereUpdatedAt($value)
 */
	class User extends \Eloquent {}
}

