<?php

namespace App\Http\Controllers;

use App\Models\Pengemasan;
use Illuminate\Http\Request;
use Carbon\Carbon;

class PengemasanReportController extends Controller
{
    public function index(Request $request)
    {
        // Check access: must have role
        if (!auth()->user()->role) {
            abort(403, 'Unauthorized access.');
        }

        $tanggal = $request->input('tanggal', Carbon::today()->format('Y-m-d'));
        $gilir = $request->input('gilir');

        $reports = $this->getReportData($tanggal, $gilir);

        return view('pengemasan.report.index', compact('reports', 'tanggal', 'gilir'));
    }

    /**
     * Tampilan cetak laporan pengemasan.
     */
    public function print(Request $request)
    {
        // Check access: must have role
        if (!auth()->user()->role) {
            abort(403, 'Unauthorized access.');
        }

        $tanggal = $request->input('tanggal', Carbon::today()->format('Y-m-d'));
        $gilir = $request->input('gilir');
        $petugas = $request->input('petugas');
        $penanggung_jawab = $request->input('penanggung_jawab');

        $reports = $this->getReportData($tanggal, $gilir);

        return view('pengemasan.report.print', compact('reports', 'tanggal', 'gilir', 'petugas', 'penanggung_jawab'));
    }

    /**
     * Helper untuk mengambil dan memproses data laporan dengan logika penggabungan. 
     */
    private function getReportData($tanggal, $gilir)
    {
        $query = Pengemasan::where('tanggal_pengemasan', $tanggal);

        if ($gilir) {
            $query->where('gilir', $gilir);
        }

        $data = $query->orderBy('pecahan')
            ->orderBy('tahun_anggaran')
            ->orderBy('tahun_emisi')
            ->orderBy('dus_awal')
            ->get();

        $processed = [];
        foreach ($data as $row) {
            if (empty($processed)) {
                $processed[] = $this->formatRow($row);
                continue;
            }

            $lastIndex = count($processed) - 1;
            $last = &$processed[$lastIndex];

            // logika : jika pecahan, ta, te sama dan nomor dus berurutan maka digabung
            if (
                $last['pecahan'] == $row->pecahan &&
                $last['ta'] == $row->tahun_anggaran &&
                $last['te'] == $row->tahun_emisi &&
                $row->dus_awal == $last['nomor_dus_akhir'] + 1
            ) {

                $last['nomor_dus_akhir'] = $row->dus_akhir;
                $last['jumlah_bilyet'] += $row->total_bilyet;
                $last['jumlah_dus'] += $row->jumlah_dus;
            } else {
                $processed[] = $this->formatRow($row);
            }
        }

        return $processed;
    }

    /**
     * Helper untuk memformat satu baris untuk laporan.
     */
    private function formatRow($row)
    {
        return [
            'pecahan' => $row->pecahan,
            'ta' => $row->tahun_anggaran,
            'te' => $row->tahun_emisi,
            'nomor_dus_awal' => $row->dus_awal,
            'nomor_dus_akhir' => $row->dus_akhir,
            'jumlah_bilyet' => $row->total_bilyet,
            'jumlah_dus' => $row->jumlah_dus,
        ];
    }
}
