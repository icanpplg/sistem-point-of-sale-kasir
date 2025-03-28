<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\Barang;
use App\Models\Category;
use App\Models\Satuan;
use App\Models\Laporan;

class AdminDashboardController extends Controller
{
    public function index()
    {
        $data = [
            'namaBarang'     => Barang::count(),
            'stokBarang'     => Barang::sum('stok'),
            'laporanhasil'   => Laporan::sum('total'),
            'barangterjual'  => Laporan::sum('jumlah'),
            'totalLaporan'   => Laporan::count(),
            'categoryBarang' => Category::count(),
            'totalSatuan'    => Satuan::count(),
            // Menghitung total keuntungan (Total Penjualan - Total Modal)
            'totalKeuntungan' => Laporan::sum('total') - Laporan::sum(\DB::raw('modal * jumlah'))
        ];

        // Ambil filter yang disimpan dari halaman laporan (jika ada)
        $filterTanggal = session('filter_tanggal');
        $filterBulan   = session('filter_bulan');
        $filterTahun   = session('filter_tahun');

        // Siapkan query dasar untuk Laporan
        $query = Laporan::query();
        if ($filterTanggal) {
            $query->whereDate('transaction_date', $filterTanggal);
        }
        if ($filterBulan) {
            $year = substr($filterBulan, 0, 4);
            $month = substr($filterBulan, 5, 2);
            $query->whereYear('transaction_date', $year)
                  ->whereMonth('transaction_date', $month);
        }
        if ($filterTahun) {
            $query->whereYear('transaction_date', $filterTahun);
        }

        // Clone query untuk masing-masing chart
        $salesPerDay = (clone $query)
            ->selectRaw('DATE(transaction_date) as date, SUM(total) as total')
            ->groupBy('date')
            ->orderBy('date', 'asc')
            ->get();

        $salesPerMonth = (clone $query)
            ->selectRaw("DATE_FORMAT(transaction_date, '%Y-%m') as month, SUM(total) as total")
            ->groupBy('month')
            ->orderBy('month', 'asc')
            ->get();

        $salesPerYear = (clone $query)
            ->selectRaw('YEAR(transaction_date) as year, SUM(total) as total')
            ->groupBy('year')
            ->orderBy('year', 'asc')
            ->get();

        return view('admin.dashboard.index', compact('data', 'salesPerDay', 'salesPerMonth', 'salesPerYear'));
    }
}
