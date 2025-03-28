@extends('layouts.app')

@section('title', 'Halaman Dashboard')

@section('content')
  <!-- Bagian Dashboard Card -->
  <section id="dashboard" class="mb-12">
    <h2 class="text-3xl font-semibold text-gray-800 dark:text-gray-100 mb-8">Dashboard</h2>
    <div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-4 gap-6">
      <!-- Data Barang -->
      <div class="card bg-white dark:bg-gray-800 shadow rounded-lg">
        <div class="p-6 flex flex-col items-start">
          <span class="material-icons card-icon text-blue-600 dark:text-blue-400">shopping_cart</span>
          <a href="{{ url('admin/dashboard/stokbarang') }}" class="mt-4 text-blue-600 dark:text-white text-lg font-semibold">
            Data Barang
          </a>
          <p class="mt-2 text-2xl font-bold text-black dark:text-white">{{ $data['namaBarang'] }}</p>
        </div>
      </div>
      <!-- Data Laporan -->
      <div class="card bg-white dark:bg-gray-800 shadow rounded-lg">
        <div class="p-6 flex flex-col items-start">
          <span class="material-icons card-icon text-blue-600 dark:text-blue-400">bar_chart</span>
          <a href="{{ url('admin/dashboard/laporanpenjualan') }}" class="mt-4 text-blue-600 dark:text-white text-lg font-semibold">
            Data Laporan
          </a>
          <p class="mt-2 text-2xl font-bold text-black dark:text-white">{{ $data['totalLaporan'] }}</p>
        </div>
      </div>
      <!-- Stok Barang -->
      <div class="card bg-white dark:bg-gray-800 shadow rounded-lg">
        <div class="p-6 flex flex-col items-start">
          <span class="material-icons card-icon text-blue-600 dark:text-blue-400">inventory</span>
          <a href="{{ url('admin/dashboard/stokbarang') }}" class="mt-4 text-blue-600 dark:text-white text-lg font-semibold">
            Stok Barang
          </a>
          <p class="mt-2 text-2xl font-bold text-black dark:text-white">{{ $data['stokBarang'] }}</p>
        </div>
      </div>
      <!-- Barang Terjual -->
      <div class="card bg-white dark:bg-gray-800 shadow rounded-lg">
        <div class="p-6 flex flex-col items-start">
          <span class="material-icons card-icon text-blue-600 dark:text-blue-400">shopping_bag</span>
          <a href="{{ url('admin/dashboard/laporanpenjualan') }}" class="mt-4 text-blue-600 dark:text-white text-lg font-semibold">
            Barang Terjual
          </a>
          <p class="mt-2 text-2xl font-bold text-black dark:text-white">{{ $data['barangterjual'] }}</p>
        </div>
      </div>
      <!-- Kategori Barang -->
      <div class="card bg-white dark:bg-gray-800 shadow rounded-lg">
        <div class="p-6 flex flex-col items-start">
          <span class="material-icons card-icon text-blue-600 dark:text-blue-400">category</span>
          <a href="{{ url('admin/dashboard/kategori') }}" class="mt-4 text-blue-600 dark:text-white text-lg font-semibold">
            Kategori Barang
          </a>
          <p class="mt-2 text-2xl font-bold text-black dark:text-white">{{ $data['categoryBarang'] }}</p>
        </div>
      </div>
      <!-- Satuan Barang -->
      <div class="card bg-white dark:bg-gray-800 shadow rounded-lg">
        <div class="p-6 flex flex-col items-start">
          <span class="material-icons card-icon text-blue-600 dark:text-blue-400">straighten</span>
          <a href="{{ url('admin/dashboard/satuan') }}" class="mt-4 text-blue-600 dark:text-white text-lg font-semibold">
            Satuan Barang
          </a>
          <p class="mt-2 text-2xl font-bold text-black dark:text-white">{{ $data['totalSatuan'] }}</p>
        </div>
      </div>
      <!-- Total Hasil -->
      <div class="card bg-white dark:bg-gray-800 shadow rounded-lg">
        <div class="p-6 flex flex-col items-start">
          <span class="material-icons card-icon text-blue-600 dark:text-blue-400">attach_money</span>
          <a href="{{ url('admin/dashboard/laporanpenjualan') }}" class="mt-4 text-blue-600 dark:text-white text-lg font-semibold">
            Total Transaksi
          </a>
          <p class="mt-2 text-2xl font-bold text-black dark:text-white">{{ $data['laporanhasil'] }}</p>
        </div>
      </div>
      <!-- Keuntungan -->
      <div class="card bg-white dark:bg-gray-800 shadow rounded-lg">
        <div class="p-6 flex flex-col items-start">
          <span class="material-icons card-icon text-blue-600 dark:text-blue-400">trending_up</span>
          <a href="{{ url('admin/dashboard/laporanpenjualan') }}" class="mt-4 text-blue-600 dark:text-white text-lg font-semibold">
            Keuntungan
          </a>
          <p class="mt-2 text-2xl font-bold text-black dark:text-white">{{ $data['totalKeuntungan'] }}</p>
        </div>
      </div>
    </div>
  </section>

  <!-- Bagian Diagram/Chart Penjualan -->
  <section id="chart-section" class="mt-12">
    <h2 class="text-2xl font-semibold text-gray-800 dark:text-gray-100 mb-4">Diagram Penjualan</h2>
    <div class="grid grid-cols-1 md:grid-cols-3 gap-6">
      <!-- Chart Penjualan Per Hari -->
      <div class="card p-4 bg-white dark:bg-gray-800 shadow rounded">
        <h3 class="text-lg font-semibold mb-2">Penjualan Per Hari</h3>
        <canvas id="salesPerDayChart"></canvas>
      </div>
      <!-- Chart Penjualan Per Bulan -->
      <div class="card p-4 bg-white dark:bg-gray-800 shadow rounded">
        <h3 class="text-lg font-semibold mb-2">Penjualan Per Bulan</h3>
        <canvas id="salesPerMonthChart"></canvas>
      </div>
      <!-- Chart Penjualan Per Tahun -->
      <div class="card p-4 bg-white dark:bg-gray-800 shadow rounded">
        <h3 class="text-lg font-semibold mb-2">Penjualan Per Tahun</h3>
        <canvas id="salesPerYearChart"></canvas>
      </div>
    </div>
  </section>

  @if(session('status'))
    <script>
      Swal.fire({
        title: 'Berhasil!',
        text: '{{ session('status') }}',
        icon: 'success',
        confirmButtonText: 'OK',
        showCancelButton: false,
        customClass: {
          confirmButton: 'bg-blue-600 hover:bg-blue-700 text-white font-bold py-2 px-4 rounded'
        }
      });
    </script>
  @endif
@endsection

@push('scripts')
  <!-- Script Chart.js -->
  <script>
    document.addEventListener("DOMContentLoaded", function() {
      // Chart Penjualan Per Hari
      var ctxDay = document.getElementById('salesPerDayChart').getContext('2d');
      new Chart(ctxDay, {
        type: 'line',
        data: {
          labels: {!! json_encode($salesPerDay->pluck('date')) !!},
          datasets: [{
            label: 'Penjualan Harian',
            data: {!! json_encode($salesPerDay->pluck('total')) !!},
            borderColor: 'blue',
            backgroundColor: 'rgba(0, 0, 255, 0.1)',
            borderWidth: 2,
            fill: true
          }]
        },
        options: {
          responsive: true,
          scales: {
            y: { beginAtZero: true }
          }
        }
      });

      // Chart Penjualan Per Bulan
      var ctxMonth = document.getElementById('salesPerMonthChart').getContext('2d');
      new Chart(ctxMonth, {
        type: 'bar',
        data: {
          labels: {!! json_encode($salesPerMonth->pluck('month')) !!},
          datasets: [{
            label: 'Penjualan Bulanan',
            data: {!! json_encode($salesPerMonth->pluck('total')) !!},
            backgroundColor: 'orange',
            borderWidth: 1
          }]
        },
        options: {
          responsive: true,
          scales: {
            y: { beginAtZero: true }
          }
        }
      });

      // Chart Penjualan Per Tahun
      var ctxYear = document.getElementById('salesPerYearChart').getContext('2d');
      new Chart(ctxYear, {
        type: 'pie',
        data: {
          labels: {!! json_encode($salesPerYear->pluck('year')) !!},
          datasets: [{
            label: 'Penjualan Tahunan',
            data: {!! json_encode($salesPerYear->pluck('total')) !!},
            backgroundColor: ['red', 'green', 'blue', 'purple', 'yellow'],
            borderWidth: 1
          }]
        },
        options: {
          responsive: true
        }
      });
    });
  </script>
@endpush
