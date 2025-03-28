<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\Laporan;
use App\Exports\LaporanExport;
use Maatwebsite\Excel\Facades\Excel;

class AdminlaporanpenjualanController extends Controller
{
    /**
     * Tampilkan halaman laporan penjualan.
     */
    public function index()
    {
        // Data awal (tanpa filter)
        $laporans = Laporan::orderBy('transaction_date', 'desc')->get();

        // Hitung total terjual, total transaksi, & total keuntungan
        $totalTerjual    = $laporans->sum('jumlah');
        $totalTransaksi  = $laporans->sum('total');
        $totalKeuntungan = $laporans->sum(function($laporan) {
            return $laporan->total - ($laporan->modal * $laporan->jumlah);
        });

        return view('admin.laporanpenjualan.index', compact(
            'laporans',
            'totalTerjual',
            'totalTransaksi',
            'totalKeuntungan'
        ));
    }

    /**
     * Filter laporan (AJAX).
     */
    public function filter(Request $request)
    {
        $query = Laporan::query();

        // Filter per hari
        if ($request->filled('tanggal')) {
            $query->whereDate('transaction_date', $request->tanggal);
        }
        // Filter per bulan (format YYYY-MM)
        if ($request->filled('bulan')) {
            $year  = substr($request->bulan, 0, 4);
            $month = substr($request->bulan, 5, 2);
            $query->whereYear('transaction_date', $year)
                  ->whereMonth('transaction_date', $month);
        }
        // Filter per tahun
        if ($request->filled('tahun')) {
            $query->whereYear('transaction_date', $request->tahun);
        }

        $laporans = $query->orderBy('transaction_date', 'desc')->get();

        $totalTerjual    = $laporans->sum('jumlah');
        $totalTransaksi  = $laporans->sum('total');
        $totalKeuntungan = $laporans->sum(function($laporan) {
            return $laporan->total - ($laporan->modal * $laporan->jumlah);
        });

        return response()->json([
            'laporans'        => $laporans,
            'totalTerjual'    => $totalTerjual,
            'totalTransaksi'  => $totalTransaksi,
            'totalKeuntungan' => $totalKeuntungan,
        ]);
    }

    /**
     * Export laporan ke Excel (nama file dinamis + timestamp).
     */
    public function exportExcel(Request $request)
    {
        $query = Laporan::query();

        // Ambil filter
        $tanggal = $request->get('tanggal');
        $bulan   = $request->get('bulan');
        $tahun   = $request->get('tahun');

        // Gunakan timestamp agar nama file selalu unik
        // Format: YYYYMMDD_HHMMSS (misal: 20250301_174500)
        $timestamp = date('Ymd_His');

        // Nama file dasar
        $filename = 'laporan_penjualan_filter_' . $timestamp;

        // Jika ada filter harian
        if (!empty($tanggal)) {
            // Tambahkan info harian ke nama file
            $filename .= '_harian_' . $tanggal;
            $query->whereDate('transaction_date', $tanggal);
        }

        // Jika ada filter bulanan (YYYY-MM)
        if (!empty($bulan)) {
            // Tambahkan info bulanan ke nama file
            $filename .= '_bulanan_' . $bulan;
            $year  = substr($bulan, 0, 4);
            $month = substr($bulan, 5, 2);
            $query->whereYear('transaction_date', $year)
                  ->whereMonth('transaction_date', $month);
        }

        // Jika ada filter tahunan
        if (!empty($tahun)) {
            // Tambahkan info tahunan ke nama file
            $filename .= '_tahunan_' . $tahun;
            $query->whereYear('transaction_date', $tahun);
        }

        // Ambil data
        $laporans = $query->orderBy('transaction_date', 'desc')->get();

        $totalTerjual    = $laporans->sum('jumlah');
        $totalTransaksi  = $laporans->sum('total');
        $totalKeuntungan = $laporans->sum(function($laporan) {
            return $laporan->total - ($laporan->modal * $laporan->jumlah);
        });

        // Ekspor ke Excel
        return Excel::download(
            new LaporanExport(
                $laporans,
                $totalTerjual,
                $totalTransaksi,
                $totalKeuntungan,
                $filename
            ),
            $filename . '.xlsx'
        );
    }
}
