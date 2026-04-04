<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

use App\Models\BahanPenolong;
use App\Models\BahanPenolongTransaction;
use App\Models\User;
use Illuminate\Support\Facades\DB;

class BahanPenolongSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $admin = User::where('role', 'admin')->first() ?? User::first();
        
        $materials = [
            [
                'nama_bahan' => 'Inner Box (Dus Dalam)',
                'kode_material' => 'IB-001',
                'satuan' => 'pcs',
                'stok' => 500,
                'min_stok' => 100,
                'keterangan' => 'Kotak kemasan dalam untuk 10 pack'
            ],
            [
                'nama_bahan' => 'Outer Box (Dus Luar)',
                'kode_material' => 'OB-002',
                'satuan' => 'pcs',
                'stok' => 50,
                'min_stok' => 10,
                'keterangan' => 'Kotak kemasan luar untuk 500 pack'
            ],
            [
                'nama_bahan' => 'Plastik PE (Roll)',
                'kode_material' => 'PL-003',
                'satuan' => 'roll',
                'stok' => 20,
                'min_stok' => 5,
                'keterangan' => 'Plastik pembungkus tahan panas'
            ],
            [
                'nama_bahan' => 'Selotip Khazpro',
                'kode_material' => 'ST-004',
                'satuan' => 'roll',
                'stok' => 100,
                'min_stok' => 20,
                'keterangan' => 'Selotip bening dengan logo'
            ],
            [
                'nama_bahan' => 'Ribbon Barcode (Premium)',
                'kode_material' => 'RB-005',
                'satuan' => 'roll',
                'stok' => 15,
                'min_stok' => 3,
                'keterangan' => 'Tinta ribbon untuk printer barcode'
            ],
        ];

        DB::transaction(function () use ($materials, $admin) {
            foreach ($materials as $m) {
                $material = BahanPenolong::create($m);

                // Buat transaksi awal sebagai saldo awal
                BahanPenolongTransaction::create([
                    'bahan_penolong_id' => $material->id,
                    'tipe' => 'masuk',
                    'kategori' => 'penerimaan',
                    'jumlah' => $m['stok'],
                    'stok_akhir' => $m['stok'],
                    'keterangan' => 'Saldo awal sistem',
                    'created_by' => $admin->id,
                ]);
            }
        });
    }
}
