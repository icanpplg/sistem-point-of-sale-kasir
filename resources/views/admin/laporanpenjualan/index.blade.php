@extends('layouts.app')

@section('title', 'Laporan Penjualan')

@section('content')
<header class="mb-8">
  <h1 class="text-4xl font-bold text-gray-800 dark:text-gray-100">Laporan Penjualan</h1>
  <p class="text-gray-600 dark:text-gray-300 text-lg">
    Laporan penjualan berdasarkan Per Hari, Per Bulan, dan Pertahun
  </p>
</header>

<!-- Tab Navigation -->
<div class="mb-8">
  <ul class="flex border-b">
    <li class="-mb-px mr-1">
      <a id="tab-hari" href="#" class="bg-white inline-block py-2 px-4 font-semibold text-blue-600 border-l border-t border-r rounded-t">
        Per Hari
      </a>
    </li>
    <li class="mr-1">
      <a id="tab-bulan" href="#" class="bg-white inline-block py-2 px-4 font-semibold text-blue-600 border-l border-t border-r rounded-t">
        Per Bulan
      </a>
    </li>
    <li class="mr-1">
      <a id="tab-tahun" href="#" class="bg-white inline-block py-2 px-4 font-semibold text-blue-600 border-l border-t border-r rounded-t">
        Pertahun
      </a>
    </li>
  </ul>
</div>

<!-- Form: Laporan Per Hari -->
<section id="form-hari" class="mb-8">
  <div class="bg-white dark:bg-gray-800 p-6 rounded shadow">
    <h2 class="text-xl font-medium mb-4">Cari Laporan Per Hari</h2>
    <div class="flex flex-col sm:flex-row items-center gap-4">
      <!-- Input Tanggal -->
      <div class="flex flex-col">
        <label for="hari" class="mb-1 font-medium">Pilih Hari</label>
        <input type="date" id="hari"
               class="border border-gray-300 dark:border-gray-700 rounded p-2 bg-white dark:bg-gray-700 text-gray-800 dark:text-gray-100" />
      </div>
      <!-- Tombol Aksi -->
      <div class="flex flex-col">
        <label class="invisible mb-1">Aksi</label>
        <div class="flex gap-2">
          <button id="cariHari" class="bg-blue-600 text-white py-2 px-4 rounded hover:bg-blue-700">Cari</button>
          <button id="refreshHari" class="bg-yellow-600 text-white py-2 px-4 rounded hover:bg-yellow-700">Refresh</button>
          <button id="exportExcelHari" class="bg-green-600 text-white py-2 px-4 rounded hover:bg-green-700">Export Excel</button>
        </div>
      </div>
    </div>
  </div>
</section>

<!-- Form: Laporan Per Bulan -->
<section id="form-bulan" class="mb-8 hidden">
  <div class="bg-white dark:bg-gray-800 p-6 rounded shadow">
    <h2 class="text-xl font-medium mb-4">Cari Laporan Per Bulan</h2>
    <div class="flex flex-col sm:flex-row items-center gap-2">
      <!-- Input Bulan -->
      <div class="flex flex-col">
        <label for="bulanInput" class="mb-1 font-medium">Pilih Bulan</label>
        <input type="month" id="bulanInput"
               class="border border-gray-300 dark:border-gray-700 rounded p-2 bg-white dark:bg-gray-700 text-gray-800 dark:text-gray-100">
      </div>
      <!-- Tombol Aksi -->
      <div class="flex flex-col">
        <label class="invisible mb-1">Aksi</label>
        <div class="flex gap-2">
          <button id="cariBulan" class="bg-blue-600 text-white py-2 px-4 rounded hover:bg-blue-700">Cari</button>
          <button id="refreshBulan" class="bg-yellow-600 text-white py-2 px-4 rounded hover:bg-yellow-700">Refresh</button>
          <button id="exportExcelBulan" class="bg-green-600 text-white py-2 px-4 rounded hover:bg-green-700">Export Excel</button>
        </div>
      </div>
    </div>
  </div>
</section>

<!-- Form: Laporan Per Tahun -->
<section id="form-tahun" class="mb-8 hidden">
  <div class="bg-white dark:bg-gray-800 p-6 rounded shadow">
    <h2 class="text-xl font-medium mb-4">Cari Laporan Per Tahun</h2>
    <div class="flex flex-col sm:flex-row items-center gap-2">
      <!-- Pilih Tahun (Dropdown) -->
      <div class="flex flex-col">
        <label for="tahunInput" class="mb-1 font-medium">Pilih Tahun</label>
        <select id="tahunInput"
                class="border border-gray-300 dark:border-gray-700 rounded p-2 bg-white dark:bg-gray-700 text-gray-800 dark:text-gray-100">
          <!-- Akan diisi via JS -->
        </select>
      </div>
      <!-- Tombol Aksi -->
      <div class="flex flex-col">
        <label class="invisible mb-1">Aksi</label>
        <div class="flex gap-2">
          <button id="cariTahun" class="bg-blue-600 text-white py-2 px-4 rounded hover:bg-blue-700">Cari</button>
          <button id="refreshTahun" class="bg-yellow-600 text-white py-2 px-4 rounded hover:bg-yellow-700">Refresh</button>
          <button id="exportExcelTahun" class="bg-green-600 text-white py-2 px-4 rounded hover:bg-green-700">Export Excel</button>
        </div>
      </div>
    </div>
  </div>
</section>

<!-- Tabel Laporan Penjualan -->
<section>
  <div class="bg-white dark:bg-gray-800 p-4 sm:p-6 rounded-lg shadow-lg">
    <div class="overflow-x-auto">
      <table id="laporanTable"
             class="min-w-full bg-white dark:bg-gray-800 shadow rounded-lg border border-gray-200 dark:border-gray-700 text-xs sm:text-sm">
        <thead class="bg-blue-600 text-white">
          <tr>
            <th class="py-2 px-2 text-left uppercase">No</th>
            <th class="py-2 px-2 text-left uppercase">Kode Barang</th>
            <th class="py-2 px-2 text-left uppercase">Nama Barang</th>
            <th class="py-2 px-2 text-right uppercase">Jumlah</th>
            <th class="py-2 px-2 text-right uppercase">Modal</th>
            <th class="py-2 px-2 text-right uppercase">Total</th>
            <th class="py-2 px-2 text-left uppercase">Kasir</th>
            <th class="py-2 px-2 text-center uppercase">Tanggal Transaksi</th>
          </tr>
        </thead>
        <tbody id="laporanBody" class="divide-y divide-gray-200 dark:divide-gray-700">
          @foreach($laporans as $index => $laporan)
          <tr class="hover:bg-gray-50 dark:hover:bg-gray-700 transition">
            <td class="py-2 px-2">{{ $index + 1 }}</td>
            <td class="py-2 px-2">{{ $laporan->kode_barang }}</td>
            <td class="py-2 px-2">{{ $laporan->nama_barang }}</td>
            <td class="py-2 px-2 text-right">{{ $laporan->jumlah }}</td>
            <td class="py-2 px-2 text-right">Rp {{ number_format($laporan->modal, 0, ',', '.') }}</td>
            <td class="py-2 px-2 text-right">Rp {{ number_format($laporan->total, 0, ',', '.') }}</td>
            <td class="py-2 px-2">{{ $laporan->kasir }}</td>
            <td class="py-2 px-2 text-center">
              {{ \Carbon\Carbon::parse($laporan->transaction_date)->translatedFormat('l, d F Y H:i') }}
            </td>
          </tr>
          @endforeach
        </tbody>
        <tfoot>
          <!-- Baris Total Terjual -->
          <tr class="border-t border-gray-200 dark:border-gray-600">
            <td colspan="3"
                class="py-3 px-4 text-right text-sm font-medium text-gray-600 dark:text-gray-300">
              Total Terjual:
            </td>
            <td id="totalTerjual"
                class="py-3 px-4 text-right text-lg font-bold text-gray-800 dark:text-gray-100">
              {{ $totalTerjual }}
            </td>
            <td colspan="4"></td>
          </tr>
          <!-- Baris Total Transaksi -->
          <tr class="border-t border-gray-200 dark:border-gray-600">
            <td colspan="3"
                class="py-3 px-4 text-right text-sm font-semibold text-gray-600 dark:text-gray-300">
              Total Transaksi:
            </td>
            <td colspan="4"
                class="py-3 px-4 text-right text-lg font-bold text-gray-800 dark:text-gray-100">
              Rp {{ number_format($laporans->sum('total'), 0, ',', '.') }}
            </td>
          </tr>
          <!-- Baris Keuntungan -->
          <tr class="border-t border-gray-200 dark:border-gray-600">
            <td colspan="6"
                class="py-3 px-4 text-right text-sm font-semibold text-gray-600 dark:text-gray-300">
              Keuntungan:
            </td>
            <td colspan="2" id="totalKeuntungan"
                class="py-3 px-4 text-right text-lg font-bold text-blue-600 dark:text-blue-400">
              Rp {{ number_format($totalKeuntungan, 0, ',', '.') }}
            </td>
          </tr>
        </tfoot>
      </table>
    </div>
  </div>
</section>
@endsection

@push('scripts')
<script>
  // Fungsi menampilkan toast (SweetAlert2)
  function showToast(icon, title) {
    Swal.fire({
      toast: true,
      icon: icon,
      title: title,
      position: 'top-end',
      showConfirmButton: false,
      timer: 2000,
      timerProgressBar: true,
    });
  }

  // Update tabel di halaman
  function updateTable(data) {
    const laporanBody = document.getElementById("laporanBody");
    laporanBody.innerHTML = "";

    data.laporans.forEach((laporan, index) => {
      let row = `
        <tr class="hover:bg-gray-50 dark:hover:bg-gray-700 transition">
          <td class="py-2 px-2">${index + 1}</td>
          <td class="py-2 px-2">${laporan.kode_barang}</td>
          <td class="py-2 px-2">${laporan.nama_barang}</td>
          <td class="py-2 px-2 text-right">${laporan.jumlah}</td>
          <td class="py-2 px-2 text-right">Rp ${parseInt(laporan.modal).toLocaleString('id-ID')}</td>
          <td class="py-2 px-2 text-right">Rp ${parseInt(laporan.total).toLocaleString('id-ID')}</td>
          <td class="py-2 px-2">${laporan.kasir}</td>
          <td class="py-2 px-2 text-center">
            ${new Date(laporan.transaction_date).toLocaleString('id-ID', {
              weekday: 'long',
              day: '2-digit',
              month: 'long',
              year: 'numeric',
              hour: '2-digit',
              minute: '2-digit'
            })}
          </td>
        </tr>
      `;
      laporanBody.innerHTML += row;
    });

    // Update footer
    document.getElementById("totalTerjual").innerText = data.totalTerjual;
    document.getElementById("totalKeuntungan").innerText =
      "Rp " + parseInt(data.totalKeuntungan).toLocaleString('id-ID');
  }

  // TAB Switching
  document.getElementById("tab-hari").addEventListener("click", function(e) {
    e.preventDefault();
    document.getElementById("form-hari").classList.remove("hidden");
    document.getElementById("form-bulan").classList.add("hidden");
    document.getElementById("form-tahun").classList.add("hidden");
  });
  document.getElementById("tab-bulan").addEventListener("click", function(e) {
    e.preventDefault();
    document.getElementById("form-bulan").classList.remove("hidden");
    document.getElementById("form-hari").classList.add("hidden");
    document.getElementById("form-tahun").classList.add("hidden");
  });
  document.getElementById("tab-tahun").addEventListener("click", function(e) {
    e.preventDefault();
    document.getElementById("form-tahun").classList.remove("hidden");
    document.getElementById("form-hari").classList.add("hidden");
    document.getElementById("form-bulan").classList.add("hidden");
  });

  // Filter Per Hari
  document.getElementById("cariHari").addEventListener("click", function() {
    const tanggal = document.getElementById("hari").value;
    if (!tanggal) {
      showToast('error', 'Silakan pilih tanggal!');
      return;
    }
    fetch(`{{ route('laporan.filter') }}?tanggal=${tanggal}`)
      .then(res => res.json())
      .then(data => {
        updateTable(data);
        showToast('success', 'Data berhasil dimuat!');
      })
      .catch(err => {
        console.error(err);
        showToast('error', 'Terjadi kesalahan memuat data.');
      });
  });
  document.getElementById("refreshHari").addEventListener("click", function() {
    document.getElementById("hari").value = '';
    fetch(`{{ route('laporan.filter') }}`)
      .then(res => res.json())
      .then(data => {
        updateTable(data);
        showToast('success', 'Data berhasil dimuat ulang!');
      })
      .catch(err => {
        console.error(err);
        showToast('error', 'Terjadi kesalahan memuat data.');
      });
  });

  // Filter Per Bulan
  document.getElementById("cariBulan").addEventListener("click", function() {
    const bulan = document.getElementById("bulanInput").value;
    if (!bulan) {
      showToast('error', 'Silakan pilih bulan!');
      return;
    }
    fetch(`{{ route('laporan.filter') }}?bulan=${bulan}`)
      .then(res => res.json())
      .then(data => {
        updateTable(data);
        showToast('success', 'Data berhasil dimuat!');
      })
      .catch(err => {
        console.error(err);
        showToast('error', 'Terjadi kesalahan memuat data.');
      });
  });
  document.getElementById("refreshBulan").addEventListener("click", function() {
    document.getElementById("bulanInput").value = '';
    fetch(`{{ route('laporan.filter') }}`)
      .then(res => res.json())
      .then(data => {
        updateTable(data);
        showToast('success', 'Data berhasil dimuat ulang!');
      })
      .catch(err => {
        console.error(err);
        showToast('error', 'Terjadi kesalahan memuat data.');
      });
  });

  // Filter Per Tahun
  document.getElementById("cariTahun").addEventListener("click", function() {
    const tahun = document.getElementById("tahunInput").value;
    if (!tahun) {
      showToast('error', 'Silakan pilih tahun!');
      return;
    }
    fetch(`{{ route('laporan.filter') }}?tahun=${tahun}`)
      .then(res => res.json())
      .then(data => {
        updateTable(data);
        showToast('success', 'Data berhasil dimuat!');
      })
      .catch(err => {
        console.error(err);
        showToast('error', 'Terjadi kesalahan memuat data.');
      });
  });
  document.getElementById("refreshTahun").addEventListener("click", function() {
    document.getElementById("tahunInput").selectedIndex = 0;
    fetch(`{{ route('laporan.filter') }}`)
      .then(res => res.json())
      .then(data => {
        updateTable(data);
        showToast('success', 'Data berhasil dimuat ulang!');
      })
      .catch(err => {
        console.error(err);
        showToast('error', 'Terjadi kesalahan memuat data.');
      });
  });

  // Export Excel (AJAX) + Toast
  function getFilenameFromDisposition(header) {
    const filenameRegex = /filename[^;=\n]*=((['"]).*?\2|[^;\n]*)/;
    const matches = filenameRegex.exec(header);
    if (matches != null && matches[1]) {
      return matches[1].replace(/['"]/g, '');
    }
    return 'laporan_penjualan.xlsx';
  }

  // Export Per Hari
  document.getElementById("exportExcelHari").addEventListener("click", function() {
    const tanggal = document.getElementById("hari").value;
    if (!tanggal) {
      showToast('error', 'Silakan pilih Hari!');
      return;
    }
    const url = `{{ route('laporan.export') }}?tanggal=${tanggal}`;
    fetch(url)
      .then(response => {
        if (!response.ok) {
          throw new Error('Network response was not ok');
        }
        const contentDisp = response.headers.get('content-disposition');
        let fileName = 'laporan_penjualan.xlsx';
        if (contentDisp) {
          fileName = getFilenameFromDisposition(contentDisp);
        }
        return response.blob().then(blob => ({ blob, fileName }));
      })
      .then(({ blob, fileName }) => {
        const downloadUrl = URL.createObjectURL(blob);
        const link = document.createElement('a');
        link.href = downloadUrl;
        link.download = fileName;
        document.body.appendChild(link);
        link.click();
        document.body.removeChild(link);

        showToast('success', 'File Excel berhasil diunduh!');
      })
      .catch(error => {
        console.error('Export error:', error);
        showToast('error', 'Terjadi kesalahan saat export file.');
      });
  });

  // Export Per Bulan
  document.getElementById("exportExcelBulan").addEventListener("click", function() {
    const bulan = document.getElementById("bulanInput").value;
    if (!bulan) {
      showToast('error', 'Silakan pilih bulan!');
      return;
    }
    const url = `{{ route('laporan.export') }}?bulan=${bulan}`;
    fetch(url)
      .then(response => {
        if (!response.ok) {
          throw new Error('Network response was not ok');
        }
        const contentDisp = response.headers.get('content-disposition');
        let fileName = 'laporan_penjualan.xlsx';
        if (contentDisp) {
          fileName = getFilenameFromDisposition(contentDisp);
        }
        return response.blob().then(blob => ({ blob, fileName }));
      })
      .then(({ blob, fileName }) => {
        const downloadUrl = URL.createObjectURL(blob);
        const link = document.createElement('a');
        link.href = downloadUrl;
        link.download = fileName;
        document.body.appendChild(link);
        link.click();
        document.body.removeChild(link);

        showToast('success', 'File Excel berhasil diunduh!');
      })
      .catch(error => {
        console.error('Export error:', error);
        showToast('error', 'Terjadi kesalahan saat export file.');
      });
  });

  // Export Per Tahun
  document.getElementById("exportExcelTahun").addEventListener("click", function() {
    const tahun = document.getElementById("tahunInput").value;
    if (!tahun) {
      showToast('error', 'Silakan pilih tahun!');
      return;
    }
    const url = `{{ route('laporan.export') }}?tahun=${tahun}`;
    fetch(url)
      .then(response => {
        if (!response.ok) {
          throw new Error('Network response was not ok');
        }
        const contentDisp = response.headers.get('content-disposition');
        let fileName = 'laporan_penjualan.xlsx';
        if (contentDisp) {
          fileName = getFilenameFromDisposition(contentDisp);
        }
        return response.blob().then(blob => ({ blob, fileName }));
      })
      .then(({ blob, fileName }) => {
        const downloadUrl = URL.createObjectURL(blob);
        const link = document.createElement('a');
        link.href = downloadUrl;
        link.download = fileName;
        document.body.appendChild(link);
        link.click();
        document.body.removeChild(link);

        showToast('success', 'File Excel berhasil diunduh!');
      })
      .catch(error => {
        console.error('Export error:', error);
        showToast('error', 'Terjadi kesalahan saat export file.');
      });
  });

  // Isi dropdown tahun (misalnya 2025 hingga 2050)
  function populateYearDropdown() {
    const select = document.getElementById("tahunInput");
    select.innerHTML = '<option value="">-- Pilih Tahun --</option>';
    const startYear = 2025;
    const endYear = 2050;
    for (let year = startYear; year <= endYear; year++) {
      const option = document.createElement("option");
      option.value = year;
      option.textContent = year;
      select.appendChild(option);
    }
  }
  document.addEventListener("DOMContentLoaded", populateYearDropdown);
</script>
@endpush
