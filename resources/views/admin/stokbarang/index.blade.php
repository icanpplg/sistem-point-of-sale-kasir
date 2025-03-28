@extends('layouts.app')

@section('title', 'Manajemen Stok Barang')



@section('content')
  <!-- Konten Utama -->

    <!-- Header -->
    <header class="mb-8">
      <h1 class="text-4xl font-bold text-gray-800 dark:text-gray-100">Manajemen Stok Barang</h1>
      <p class="text-gray-600 dark:text-gray-300 text-lg">Kelola data stok barang dan inventaris produk</p>
    </header>

    <!-- Section Stock Barang -->
    <section id="stock" class="mb-8">
      <div class="bg-white dark:bg-gray-800 p-4 sm:p-6 rounded-lg shadow-lg">
        <div class="mb-4 flex flex-col sm:flex-row justify-between items-center space-y-2 sm:space-y-0 sm:space-x-4">
          <!-- Tombol Import Data Excel -->
          <button id="openImportButton" class="w-full sm:w-auto bg-green-600 text-white py-2 px-4 rounded hover:bg-green-700 transition text-sm flex items-center gap-2">
            <i class="fas fa-file-import"></i>
            Import Data Excel
          </button>
          <!-- Tombol Reset Semua Data -->
          <button id="resetAllDataButton" class="w-full sm:w-auto bg-red-600 text-white py-2 px-4 rounded hover:bg-red-700 transition text-sm flex items-center justify-center">
            <span class="material-icons mr-1">restore</span>
            Reset Semua Data
          </button>
        </div>

        <!-- Tabel Stok Barang -->
        <div class="overflow-x-auto">
          <table id="stockTable" class="min-w-full bg-white dark:bg-gray-800 shadow rounded-lg border border-gray-200 dark:border-gray-700 text-xs sm:text-sm">
            <thead class="bg-blue-600 text-white sticky top-0 z-10">
              <tr>
                <th>No</th>
                <th>Kode</th>
                <th>Kategori</th>
                <th>Merek</th>
                <th>Nama Produk</th>
                <th class="text-right">Harga Beli</th>
                <th class="text-right">Harga Jual</th>
                <th class="text-center">Satuan</th>
                <th class="text-center">Stok</th>
                <th class="text-center">Tanggal Buat</th>
                <th class="text-center">Aksi</th>
              </tr>
            </thead>
            <tbody class="divide-y divide-gray-200 dark:divide-gray-700">
              @foreach ($barangs as $barang)
                <tr data-id="{{ $barang->id }}" class="hover:bg-gray-50 dark:hover:bg-gray-700 transition">
                  <td></td>
                  <td>{{ $barang->kode_barang }}</td>
                  <td>
                    @if($barang->category)
                      {{ $barang->category->name }}
                    @else
                      -
                    @endif
                  </td>
                  <td>{{ $barang->merek }}</td>
                  <td>{{ $barang->nama_produk }}</td>
                  <td class="text-right">Rp {{ number_format($barang->harga_beli, 2, ',', '.') }}</td>
                  <td class="text-right">Rp {{ number_format($barang->harga_jual, 2, ',', '.') }}</td>
                  <td class="text-center">
                    @if($barang->satuanRelation)
                      {{ $barang->satuanRelation->name }}
                    @else
                      -
                    @endif
                  </td>
                  <td class="text-center">{{ $barang->stok }}</td>
                  <td class="text-center">
                    @if($barang->created_at)
                      {{ $barang->created_at->format('d/m/Y H:i') }}
                    @else
                      -
                    @endif
                  </td>
                  <td class="text-center flex justify-center space-x-1">
                    <!-- Tombol Edit -->
                    <a href="#" class="text-indigo-600 hover:text-indigo-800 font-medium transition duration-150 edit-btn"
                       data-id="{{ $barang->id }}"
                       data-kategori-id="{{ $barang->kategori }}"    {{-- Pastikan kolom "kategori" di DB berisi ID --}}
                       data-kategori-name="{{ $barang->category ? $barang->category->name : '' }}"
                       data-merek="{{ $barang->merek }}"
                       data-nama_produk="{{ $barang->nama_produk }}"
                       data-harga_beli="{{ $barang->harga_beli }}"
                       data-harga_jual="{{ $barang->harga_jual }}"
                       data-satuan-id="{{ $barang->satuan }}"        {{-- Pastikan kolom "satuan" di DB berisi ID --}}
                       data-satuan-name="{{ $barang->satuanRelation ? $barang->satuanRelation->name : '' }}"
                       data-stok="{{ $barang->stok }}">
                      Edit
                    </a>
                    <!-- Tombol Hapus -->
                    <form action="{{ route('admin.stokbarang.destroy', $barang->id) }}" method="POST" class="inline delete-form">
                      @csrf
                      @method('DELETE')
                      <a href="#" class="text-red-600 hover:text-red-800 font-medium transition duration-150 delete-btn">
                        Hapus
                      </a>
                    </form>
                  </td>
                </tr>
              @endforeach
            </tbody>
          </table>
        </div>

        <!-- Tombol Floating untuk Tambah Barang -->
        <a href="javascript:void(0);" id="openFormButton" class="floating-button bg-blue-600 text-white rounded-full shadow-lg hover:bg-blue-700 transition">
          <span class="material-icons text-lg">add</span>
        </a>

        <!-- Modal Form Tambah Barang (Create) -->
        <div id="popupForm" class="fixed inset-0 flex justify-center items-center bg-black bg-opacity-50 modal-overlay modal-hidden">
          <div class="relative bg-white dark:bg-gray-800 p-6 sm:p-8 rounded-lg shadow-xl w-full sm:w-11/12 md:w-1/2 lg:w-1/3 mx-auto transform scale-95 transition-all duration-300 ease-in-out">
            <h3 class="text-lg sm:text-xl font-semibold text-gray-800 dark:text-gray-100 mb-4 text-center">Tambah Barang</h3>
            <form id="ajaxForm" method="POST" action="{{ route('admin.stokbarang.store') }}" class="space-y-4">
              @csrf
              <div class="grid grid-cols-1 sm:grid-cols-2 gap-4">
                <div>
                  <label for="kategori" class="block text-sm font-medium text-gray-700 dark:text-gray-300">Kategori</label>
                  <select id="kategori" name="kategori" required class="w-full p-3 border border-gray-200 dark:border-gray-700 rounded focus:outline-none focus:ring-2 focus:ring-blue-600 text-sm bg-white dark:bg-gray-700">
                    <option value="">Pilih Kategori</option>
                    @foreach($categories as $category)
                      <option value="{{ $category->id }}">{{ $category->name }}</option>
                    @endforeach
                  </select>
                </div>
                <div>
                  <label for="merek" class="block text-sm font-medium text-gray-700 dark:text-gray-300">Merek</label>
                  <input type="text" id="merek" name="merek" required class="w-full p-3 border border-gray-200 dark:border-gray-700 rounded focus:outline-none focus:ring-2 focus:ring-blue-600 text-sm bg-white dark:bg-gray-700" placeholder="Masukkan merek" />
                </div>
              </div>
              <div>
                <label for="nama_produk" class="block text-sm font-medium text-gray-700 dark:text-gray-300">Nama Produk</label>
                <input type="text" id="nama_produk" name="nama_produk" required class="w-full p-3 border border-gray-200 dark:border-gray-700 rounded focus:outline-none focus:ring-2 focus:ring-blue-600 text-sm bg-white dark:bg-gray-700" placeholder="Masukkan nama produk" />
              </div>
              <div class="grid grid-cols-1 sm:grid-cols-2 gap-4">
                <div>
                  <label for="harga_beli" class="block text-sm font-medium text-gray-700 dark:text-gray-300">Harga Beli</label>
                  <input type="number" id="harga_beli" name="harga_beli" required class="w-full p-3 border border-gray-200 dark:border-gray-700 rounded focus:outline-none focus:ring-2 focus:ring-blue-600 text-sm bg-white dark:bg-gray-700" placeholder="Masukkan harga beli" />
                </div>
                <div>
                  <label for="harga_jual" class="block text-sm font-medium text-gray-700 dark:text-gray-300">Harga Jual</label>
                  <input type="number" id="harga_jual" name="harga_jual" required class="w-full p-3 border border-gray-200 dark:border-gray-700 rounded focus:outline-none focus:ring-2 focus:ring-blue-600 text-sm bg-white dark:bg-gray-700" placeholder="Masukkan harga jual" />
                </div>
              </div>
              <div class="grid grid-cols-1 sm:grid-cols-2 gap-4">
                <div>
                  <label for="satuan" class="block text-sm font-medium text-gray-700 dark:text-gray-300">Satuan</label>
                  <select id="satuan" name="satuan" required class="w-full p-3 border border-gray-200 dark:border-gray-700 rounded focus:outline-none focus:ring-2 focus:ring-blue-600 text-sm bg-white dark:bg-gray-700">
                    <option value="">Pilih Satuan Barang</option>
                    @foreach($satuans as $satuan)
                      <option value="{{ $satuan->id }}">{{ $satuan->name }}</option>
                    @endforeach
                  </select>
                </div>
                <div>
                  <label for="stok" class="block text-sm font-medium text-gray-700 dark:text-gray-300">Stok</label>
                  <input type="number" id="stok" name="stok" required class="w-full p-3 border border-gray-200 dark:border-gray-700 rounded focus:outline-none focus:ring-2 focus:ring-blue-600 text-sm bg-white dark:bg-gray-700" placeholder="Masukkan stok" />
                </div>
              </div>
              <div class="flex flex-col sm:flex-row justify-end gap-2 mt-4">
                <button type="button" id="closeFormButton" class="bg-gray-200 text-gray-800 dark:bg-gray-600 dark:text-gray-200 py-2 px-4 rounded hover:bg-gray-300 transition text-sm">Batal</button>
                <button type="submit" class="bg-blue-600 text-white py-2 px-4 rounded hover:bg-blue-700 transition text-sm">Simpan</button>
              </div>
            </form>
          </div>
        </div>

        <!-- Modal Form Edit Barang (Update) -->
        <div id="popupEditForm" class="fixed inset-0 flex justify-center items-center bg-black bg-opacity-50 modal-overlay modal-hidden">
          <div class="relative bg-white dark:bg-gray-800 p-6 sm:p-8 rounded-lg shadow-xl w-full sm:w-11/12 md:w-1/2 lg:w-1/3 mx-auto transform scale-95 transition-all duration-300 ease-in-out">
            <h3 class="text-lg sm:text-xl font-semibold text-gray-800 dark:text-gray-100 mb-4 text-center">Edit Barang</h3>
            <!-- Action form akan di-set dinamis via JavaScript -->
            <form id="ajaxEditForm" method="POST" action="">
              @csrf
              @method('PUT')

              <div class="grid grid-cols-1 sm:grid-cols-2 gap-4">
                <div>
                  <label for="edit_kategori" class="block text-sm font-medium text-gray-700 dark:text-gray-300">Kategori</label>
                  <!-- data-selected untuk menyimpan nilai kategori yang dipilih -->
                  <select id="edit_kategori" name="kategori" data-selected=""
                          class="w-full p-3 border border-gray-200 dark:border-gray-700 rounded
                                 focus:outline-none focus:ring-2 focus:ring-blue-600 text-sm
                                 bg-white dark:bg-gray-700">
                    <option value="">Pilih Kategori</option>
                    @foreach($categories as $category)
                      <option value="{{ $category->id }}">{{ $category->name }}</option>
                    @endforeach
                  </select>
                </div>
                <div>
                  <label for="edit_merek" class="block text-sm font-medium text-gray-700 dark:text-gray-300">Merek</label>
                  <input type="text" id="edit_merek" name="merek" placeholder="Masukkan merek"
                         class="w-full p-3 border border-gray-200 dark:border-gray-700 rounded
                                focus:outline-none focus:ring-2 focus:ring-blue-600 text-sm
                                bg-white dark:bg-gray-700">
                </div>
              </div>

              <div>
                <label for="edit_nama_produk" class="block text-sm font-medium text-gray-700 dark:text-gray-300">Nama Produk</label>
                <input type="text" id="edit_nama_produk" name="nama_produk" placeholder="Masukkan nama produk"
                       class="w-full p-3 border border-gray-200 dark:border-gray-700 rounded
                              focus:outline-none focus:ring-2 focus:ring-blue-600 text-sm
                              bg-white dark:bg-gray-700">
              </div>

              <div class="grid grid-cols-1 sm:grid-cols-2 gap-4">
                <div>
                  <label for="edit_harga_beli" class="block text-sm font-medium text-gray-700 dark:text-gray-300">Harga Beli</label>
                  <input type="number" id="edit_harga_beli" name="harga_beli" placeholder="Masukkan harga beli"
                         class="w-full p-3 border border-gray-200 dark:border-gray-700 rounded
                                focus:outline-none focus:ring-2 focus:ring-blue-600 text-sm
                                bg-white dark:bg-gray-700">
                </div>
                <div>
                  <label for="edit_harga_jual" class="block text-sm font-medium text-gray-700 dark:text-gray-300">Harga Jual</label>
                  <input type="number" id="edit_harga_jual" name="harga_jual" placeholder="Masukkan harga jual"
                         class="w-full p-3 border border-gray-200 dark:border-gray-700 rounded
                                focus:outline-none focus:ring-2 focus:ring-blue-600 text-sm
                                bg-white dark:bg-gray-700">
                </div>
              </div>

              <div class="grid grid-cols-1 sm:grid-cols-2 gap-4">
                <div>
                  <label for="edit_satuan" class="block text-sm font-medium text-gray-700 dark:text-gray-300">Satuan</label>
                  <!-- data-selected untuk menyimpan nilai satuan yang dipilih -->
                  <select id="edit_satuan" name="satuan" data-selected=""
                          class="w-full p-3 border border-gray-200 dark:border-gray-700 rounded
                                 focus:outline-none focus:ring-2 focus:ring-blue-600 text-sm
                                 bg-white dark:bg-gray-700">
                    <option value="">Pilih Satuan Barang</option>
                    @foreach($satuans as $satuan)
                      <option value="{{ $satuan->id }}">{{ $satuan->name }}</option>
                    @endforeach
                  </select>
                </div>
                <div>
                  <label for="edit_stok" class="block text-sm font-medium text-gray-700 dark:text-gray-300">Stok</label>
                  <input type="number" id="edit_stok" name="stok" placeholder="Masukkan stok"
                         class="w-full p-3 border border-gray-200 dark:border-gray-700 rounded
                                focus:outline-none focus:ring-2 focus:ring-blue-600 text-sm
                                bg-white dark:bg-gray-700">
                </div>
              </div>

              <div class="flex flex-col sm:flex-row justify-end gap-2 mt-4">
                <button type="button" id="closeEditFormButton"
                        class="bg-gray-200 text-gray-800 dark:bg-gray-600 dark:text-gray-200
                               py-2 px-4 rounded hover:bg-gray-300 transition text-sm">
                  Batal
                </button>
                <button type="submit"
                        class="bg-blue-600 text-white py-2 px-4 rounded hover:bg-blue-700 transition text-sm">
                  Update
                </button>
              </div>
            </form>
          </div>
        </div>

        <!-- Modal Import Excel -->
        <div id="popupImportForm" class="fixed inset-0 flex justify-center items-center bg-black bg-opacity-50 modal-overlay modal-hidden">
          <div class="relative bg-white dark:bg-gray-800 p-4 sm:p-6 rounded-lg shadow-md w-full sm:w-11/12 md:w-1/2 lg:w-1/3 mx-auto transform scale-95 transition-all duration-300 ease-in-out">
            <h3 class="text-lg sm:text-xl font-semibold text-gray-800 dark:text-gray-100 mb-4 text-center">Import Data Barang</h3>
            <form action="{{ route('admin.stokbarang.import') }}" method="POST" enctype="multipart/form-data">
              @csrf
              <div class="mb-4">
                <label for="file" class="block text-sm font-medium text-gray-700 dark:text-gray-300">Upload File Excel:</label>
                <input type="file" name="file" id="file" class="w-full p-2 border border-gray-200 dark:border-gray-700 rounded bg-white dark:bg-gray-700" required />
              </div>
              <div class="flex flex-col sm:flex-row justify-end gap-2">
                <button type="button" id="closeImportButton" class="bg-gray-300 text-gray-700 dark:bg-gray-600 dark:text-gray-200 py-2 px-4 rounded hover:bg-gray-400 transition text-sm">Batal</button>
                <button type="submit" class="bg-green-600 text-white py-2 px-4 rounded hover:bg-green-700 transition text-sm">Import</button>
              </div>
            </form>
          </div>
        </div>
      </div>
    </section>
  </div>
@endsection


 

@push('scripts')
<script>
$(document).ready(function () {
  // Setup CSRF token untuk AJAX
  $.ajaxSetup({
    headers: { 'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content') }
  });

  // Inisialisasi DataTable
  var dt = $('#stockTable').DataTable({
    dom: '<"flex flex-col sm:flex-row items-center justify-between"lf>rt<"flex flex-col sm:flex-row items-center justify-between"ip>',
    language: {
      search: "",
      searchPlaceholder: "Cari data..."
    },
    pageLength: 5,
    lengthMenu: [ [5, 10, 25, 50, -1], [5, 10, 25, 50, "All"] ],
    // Biarkan DataTables menampilkan nomor otomatis di kolom pertama
    columnDefs: [
      {
        targets: 0,
        orderable: false,
        render: function (data, type, row, meta) {
          // Menampilkan nomor urut (1-based)
          return meta.row + 1;
        }
      }
    ],
    // Nonaktifkan sort default
    order: [],
    initComplete: function() {
      var searchInput = $('.dataTables_filter input');
      searchInput
        // Jika Anda tidak butuh removeClass('form-control'), boleh dihapus
        .removeClass('form-control')
        .addClass('p-2 rounded-md border border-blue-600 focus:ring focus:ring-blue-600 focus:border-blue-600')
        .css('min-width', '250px');

      $('.dataTables_filter')
        .addClass('flex items-center')
        .prepend('<span class="material-icons text-blue-600 mr-2">search</span>');

      $('.dataTables_length select')
        .addClass('p-2 rounded-md border border-blue-600 focus:ring focus:ring-blue-600 focus:border-blue-600');

      $('.dataTables_info').addClass('text-sm text-gray-700 dark:text-gray-300');

      $('.dataTables_paginate').addClass('mt-4 flex items-center justify-end space-x-2');
      $('.paginate_button')
        .addClass('px-3 py-1 rounded-md hover:bg-blue-600 hover:text-white transition')
        .css('cursor', 'pointer');
      $('.paginate_button.current').addClass('bg-blue-600 text-white');
    }
  });

  // === Modal: Tambah Barang ===
  $('#openFormButton').click(function () {
    $('#popupForm').removeClass('modal-hidden').addClass('modal-visible');
  });
  $('#closeFormButton').click(function () {
    $('#popupForm').removeClass('modal-visible').addClass('modal-hidden');
  });

  // === Modal: Import Excel ===
  $('#openImportButton').click(function () {
    $('#popupImportForm').removeClass('modal-hidden').addClass('modal-visible');
  });
  $('#closeImportButton').click(function () {
    $('#popupImportForm').removeClass('modal-visible').addClass('modal-hidden');
  });

  // === AJAX Import Excel ===
  $('#popupImportForm form').submit(function(e) {
    e.preventDefault();
    var formData = new FormData(this);

    $.ajax({
      url: $(this).attr('action'),
      type: 'POST',
      data: formData,
      contentType: false,
      processData: false,
      success: function(response) {
        Swal.fire({
          icon: 'success',
          title: 'Berhasil!',
          text: response.message,
          timer: 3000,
          showConfirmButton: false
        });
        // Tutup modal import
        $('#popupImportForm').removeClass('modal-visible').addClass('modal-hidden');

        // 1) Update tabel stok barang
        if(response.barangs) {
          var tableBody = $('#stockTable tbody');
          tableBody.empty();

          $.each(response.barangs, function(index, barang) {
            var formattedDate = barang.created_at ? barang.created_at : '-';
            var newRow = 
              `<tr data-id="${barang.id}">
                <td></td>
                <td>${barang.kode_barang}</td>
                <td>${barang.kategori}</td>
                <td>${barang.merek}</td>
                <td>${barang.nama_produk}</td>
                <td class="text-right">Rp ${Number(barang.harga_beli).toLocaleString('id-ID', { minimumFractionDigits: 2, maximumFractionDigits: 2 })}</td>
                <td class="text-right">Rp ${Number(barang.harga_jual).toLocaleString('id-ID', { minimumFractionDigits: 2, maximumFractionDigits: 2 })}</td>
                <td class="text-center">${barang.satuan}</td>
                <td class="text-center">${barang.stok}</td>
                <td class="text-center">${formattedDate}</td>
                <td class="text-center flex justify-center space-x-1">
                  <a href="#" class="text-indigo-600 hover:text-indigo-800 font-medium transition duration-150 edit-btn"
                    data-id="${barang.id}"
                    data-kategori-id="${barang.kategori_id}"
                    data-kategori-name="${barang.kategori}"
                    data-merek="${barang.merek}"
                    data-nama_produk="${barang.nama_produk}"
                    data-harga_beli="${barang.harga_beli}"
                    data-harga_jual="${barang.harga_jual}"
                    data-satuan-id="${barang.satuan_id}"
                    data-satuan-name="${barang.satuan}"
                    data-stok="${barang.stok}">
                    Edit
                  </a>
                  <form action="{{ route('admin.stokbarang.destroy', '') }}/${barang.id}" method="POST" class="inline delete-form">
                    @csrf
                    @method('DELETE')
                    <a href="#" class="text-red-600 hover:text-red-800 font-medium transition duration-150 delete-btn">
                      Hapus
                    </a>
                  </form>
                </td>
              </tr>`;
            tableBody.append(newRow);
          });

          // Re-draw DataTable
          var rows = $('#stockTable tbody tr').toArray();
          dt.clear();
          dt.rows.add(rows);
          dt.draw();
        }

        // 2) Update dropdown kategori & satuan di modal (jika server mengirim data)
        if (response.categories && response.satuans) {
          // Modal Tambah
          let kategoriSelect = $('#kategori');
          let satuanSelect   = $('#satuan');
          kategoriSelect.empty().append('<option value="">Pilih Kategori</option>');
          satuanSelect.empty().append('<option value="">Pilih Satuan Barang</option>');

          $.each(response.categories, function(index, cat) {
            kategoriSelect.append(`<option value="${cat.id}">${cat.name}</option>`);
          });
          $.each(response.satuans, function(index, sat) {
            satuanSelect.append(`<option value="${sat.id}">${sat.name}</option>`);
          });

          // Modal Edit
          let kategoriEditSelect = $('#edit_kategori');
          let satuanEditSelect   = $('#edit_satuan');
          // Ambil nilai yang sudah tersimpan (jika ada)
          let selectedKategori = kategoriEditSelect.attr('data-selected') || '';
          let selectedSatuan   = satuanEditSelect.attr('data-selected') || '';
          
          kategoriEditSelect.empty().append('<option value="">Pilih Kategori</option>');
          satuanEditSelect.empty().append('<option value="">Pilih Satuan Barang</option>');

          $.each(response.categories, function(index, cat) {
            let selected = (cat.id == selectedKategori) ? 'selected' : '';
            kategoriEditSelect.append(`<option value="${cat.id}" ${selected}>${cat.name}</option>`);
          });
          $.each(response.satuans, function(index, sat) {
            let selected = (sat.id == selectedSatuan) ? 'selected' : '';
            satuanEditSelect.append(`<option value="${sat.id}" ${selected}>${sat.name}</option>`);
          });
        }
      },
      error: function(xhr) {
        Swal.fire({
          icon: 'error',
          title: 'Error!',
          text: xhr.responseJSON.error || 'Terjadi kesalahan saat mengimport data.'
        });
      }
    });
  });

  // === Tombol Reset Semua Data ===
  $('#resetAllDataButton').click(function () {
    Swal.fire({
      title: '<i class="material-icons" style="color: #d33; font-size: 50px;">restore</i><br>Yakin ingin mereset semua data?',
      html: "<b>Semua data barang akan dihapus dan tidak bisa dikembalikan!</b>",
      showCancelButton: true,
      confirmButtonColor: '#d33',
      cancelButtonColor: '#3085d6',
      confirmButtonText: 'Ya, reset!',
      cancelButtonText: 'Batal',
      customClass: {
        popup: 'rounded-xl shadow-lg',
        confirmButton: 'bg-red-600 hover:bg-red-700 text-white font-medium px-4 py-2 rounded',
        cancelButton: 'bg-gray-300 hover:bg-gray-400 text-black font-medium px-4 py-2 rounded'
      }
    }).then((result) => {
      if (result.isConfirmed) {
        $.ajax({
          url: "{{ route('admin.stokbarang.resetAll') }}",
          type: "POST",
          dataType: "json",
          data: { _token: $('meta[name="csrf-token"]').attr('content') },
          success: function (response) {
            // Jika respons sukses (ada data yang direset)
            if (response.success) {
              Swal.fire({
                title: 'Berhasil!',
                text: response.success,
                icon: 'success', 
                timer: 2000,
                showConfirmButton: false
              });
              // Hapus semua data di DataTable tanpa reload halaman
              dt.clear().draw();
            }
            // Jika respons error (tidak ada data)
            else if (response.error) {
              Swal.fire({
                title: 'Tidak Ada Data!',
                text: response.error,
                icon: 'info',
                confirmButtonText: 'OK',
                customClass: {
                  confirmButton: 'bg-blue-600 hover:bg-blue-700 text-white font-medium px-4 py-2 rounded'
                }
              });
            }
          },
          error: function (xhr) {
            // Jika error (status code 400, dsb.)
            Swal.fire({
              title: 'Error!',
              text: xhr.responseJSON.error || 'Terjadi kesalahan saat mereset data.',
              icon: 'error',
              confirmButtonText: 'OK',
              customClass: {
                confirmButton: 'bg-blue-600 hover:bg-blue-700 text-white font-medium px-4 py-2 rounded'
              }
            });
          }
        });
      }
    });
  });

  // === AJAX Create (Tambah Barang) ===
  $('#ajaxForm').submit(function(e) {
    e.preventDefault();
    var form = $(this);
    $.ajax({
      url: form.attr('action'),
      method: form.attr('method'),
      data: form.serialize(),
      success: function(response) {
        Swal.fire({
          icon: 'success',
          title: 'Berhasil!',
          text: response.success,
          timer: 3000,
          showConfirmButton: false
        });

        var barang = response.barang;
        var formattedDate = barang.created_at ? barang.created_at : '-';

        var newRow = 
          `<tr data-id="${barang.id}">
            <td></td>
            <td>${barang.kode_barang}</td>
            <td>${barang.kategori}</td>
            <td>${barang.merek}</td>
            <td>${barang.nama_produk}</td>
            <td class="text-right">Rp ${Number(barang.harga_beli).toLocaleString('id-ID', { minimumFractionDigits: 2, maximumFractionDigits: 2 })}</td>
            <td class="text-right">Rp ${Number(barang.harga_jual).toLocaleString('id-ID', { minimumFractionDigits: 2, maximumFractionDigits: 2 })}</td>
            <td class="text-center">${barang.satuan}</td>
            <td class="text-center">${barang.stok}</td>
            <td class="text-center">${formattedDate}</td>
            <td class="text-center flex justify-center space-x-1">
              <a href="#" class="text-indigo-600 hover:text-indigo-800 font-medium transition duration-150 edit-btn"
                data-id="${barang.id}"
                data-kategori-id="${barang.kategori_id}"
                data-kategori-name="${barang.kategori}"
                data-merek="${barang.merek}"
                data-nama_produk="${barang.nama_produk}"
                data-harga_beli="${barang.harga_beli}"
                data-harga_jual="${barang.harga_jual}"
                data-satuan-id="${barang.satuan_id}"
                data-satuan-name="${barang.satuan}"
                data-stok="${barang.stok}">
                Edit
              </a>
              <form action="{{ route('admin.stokbarang.destroy', '') }}/${barang.id}" method="POST" class="inline delete-form">
                @csrf
                @method('DELETE')
                <a href="#" class="text-red-600 hover:text-red-800 font-medium transition duration-150 delete-btn">
                  Hapus
                </a>
              </form>
            </td>
          </tr>`;
        dt.row.add($(newRow)).draw();

        // Reset form & tutup modal
        form[0].reset();
        $('#popupForm').removeClass('modal-visible').addClass('modal-hidden');
      },
      error: function() {
        Swal.fire({
          icon: 'error',
          title: 'Error!',
          text: 'Gagal menambahkan barang.'
        });
      }
    });
  });
    
  // === Delegated Event: Edit Barang ===
  $(document).on('click', '.edit-btn', function(e){
    e.preventDefault();
    var id           = $(this).data('id');
    var kategoriId   = $(this).data('kategori-id');
    var merek        = $(this).data('merek');
    var namaProduk   = $(this).data('nama_produk');
    var hargaBeli    = $(this).data('harga_beli');
    var hargaJual    = $(this).data('harga_jual');
    var satuanId     = $(this).data('satuan-id');
    var stok         = $(this).data('stok');

    // Set input di modal edit
    $('#edit_merek').val(merek);
    $('#edit_nama_produk').val(namaProduk);
    $('#edit_harga_beli').val(hargaBeli);
    $('#edit_harga_jual').val(hargaJual);
    $('#edit_stok').val(stok);

    // Set dropdown (kategori & satuan)
    $('#edit_kategori').val(kategoriId);
    $('#edit_satuan').val(satuanId);

    // Simpan nilai kategori dan satuan yang dipilih
    // agar saat dropdown di-refresh (misal setelah import), kita tahu apa yang harus di-select
    $('#edit_kategori').attr('data-selected', kategoriId);
    $('#edit_satuan').attr('data-selected', satuanId);

    // Update URL form (untuk AJAX Update)
    var updateUrl = "{{ route('admin.stokbarang.update', ':id') }}".replace(':id', id);
    $('#ajaxEditForm').attr('action', updateUrl);

    // Tampilkan modal edit
    $('#popupEditForm').removeClass('modal-hidden').addClass('modal-visible');
  });

  // Tutup modal edit
  $('#closeEditFormButton').click(function(){
    $('#popupEditForm').removeClass('modal-visible').addClass('modal-hidden');
  });

  // === AJAX Update (Edit Barang) ===
  $('#ajaxEditForm').submit(function(e) {
    e.preventDefault();
    var form = $(this);

    $.ajax({
      url: form.attr('action'),
      method: form.attr('method'),
      data: form.serialize(),
      success: function(response) {
        Swal.fire({
          icon: 'success',
          title: 'Berhasil!',
          text: response.success,
          timer: 3000,
          showConfirmButton: false
        });

        var updated = response.barang;
        var id      = updated.id;      
        var row     = $('tr[data-id="'+id+'"]');

        // Perbarui tampilan tabel
        row.find('td:nth-child(3)').text(updated.kategori);
        row.find('td:nth-child(4)').text(updated.merek);
        row.find('td:nth-child(5)').text(updated.nama_produk);

        var hargaBeli = Number(updated.harga_beli).toLocaleString('id-ID', { minimumFractionDigits: 2, maximumFractionDigits: 2 });
        row.find('td:nth-child(6)').text('Rp ' + hargaBeli);

        var hargaJual = Number(updated.harga_jual).toLocaleString('id-ID', { minimumFractionDigits: 2, maximumFractionDigits: 2 });
        row.find('td:nth-child(7)').text('Rp ' + hargaJual);

        row.find('td:nth-child(8)').text(updated.satuan);
        row.find('td:nth-child(9)').text(updated.stok);

        // Perbarui data-attribute pada tombol Edit
        var editBtn = row.find('.edit-btn');
        editBtn.data('kategori-id', updated.kategori_id);
        editBtn.data('kategori-name', updated.kategori);
        editBtn.data('satuan-id', updated.satuan_id);
        editBtn.data('satuan-name', updated.satuan);
        editBtn.data('merek', updated.merek);
        editBtn.data('nama_produk', updated.nama_produk);
        editBtn.data('harga_beli', updated.harga_beli);
        editBtn.data('harga_jual', updated.harga_jual);
        editBtn.data('stok', updated.stok);

        // (Opsional) Perbarui dropdown di modal edit, jika modal masih terbuka
        $('#edit_kategori').val(updated.kategori_id);
        $('#edit_satuan').val(updated.satuan_id);

        // Tutup modal edit
        $('#popupEditForm').removeClass('modal-visible').addClass('modal-hidden');
      },
      error: function() {
        Swal.fire({
          icon: 'error',
          title: 'Error!',
          text: 'Terjadi kesalahan, silakan coba lagi.'
        });
      }
    });
  });

  // === Delegated Event: Delete Barang ===
  $(document).on('click', '.delete-btn', function(e) {
    e.preventDefault();
    var form = $(this).closest('.delete-form');
    var row = $(this).closest('tr');
    Swal.fire({
      title: 'Yakin ingin menghapus?',
      text: "Data yang dihapus tidak bisa dikembalikan!",
      imageUrl: 'https://cdn-icons-png.flaticon.com/512/1214/1214428.png',
      imageWidth: 80,
      imageHeight: 80,
      imageAlt: 'Icon Tong Sampah',
      showCancelButton: true,
      confirmButtonColor: '#d33',
      cancelButtonColor: '#3085d6',
      confirmButtonText: 'Ya, hapus!',
      cancelButtonText: 'Batal',
      customClass: {
        popup: 'rounded-xl shadow-lg',
        confirmButton: 'bg-red-600 hover:bg-red-700 text-white font-medium px-4 py-2 rounded',
        cancelButton: 'bg-gray-300 hover:bg-gray-400 text-black font-medium px-4 py-2 rounded'
      }
    }).then((result) => {
      if (result.isConfirmed) {
        $.ajax({
          url: form.attr('action'),
          method: form.attr('method'),
          data: form.serialize(),
          success: function(response) {
            Swal.fire({
              icon: 'success',
              title: 'Berhasil!',
              text: response.success,
              timer: 2000,
              showConfirmButton: false
            });
            dt.row(row).remove().draw();
          },
          error: function() {
            Swal.fire({
              icon: 'error',
              title: 'Error!',
              text: 'Terjadi kesalahan saat menghapus data.'
            });
          }
        });
      }
    });
  });
    
  // === Cek Notifikasi Stok Rendah (≤ 5) ===
  $.ajax({
    url: "{{ route('admin.stokbarang.checkLowStock') }}",
    type: "POST",
    dataType: "json",
    success: function(response) {
      if(response.length > 0) {
        var lowStockMessage = "";
        response.forEach(function(item, index) {
          lowStockMessage += (index + 1) + ". " + item.nama_produk + " (Stok: " + item.stok + ")\n";
        });
        Swal.fire({
          iconHtml: '<span class="material-icons" style="font-size:48px; color:#FFA500;">notification_important</span>',
          title: 'Stok Rendah!',
          html: "Beberapa barang memiliki stok rendah:<br><br><pre style='text-align:left; font-size:14px;'>" + lowStockMessage + "</pre>",
          confirmButtonText: 'OK',
          confirmButtonColor: '#2563EB'
        });
      }
    },
    error: function(xhr, status, error) {
      console.log("Error checking low stock: ", error);
    }
  });
});

// Jika ada flash message success, tampilkan SweetAlert
@if(session('success'))
Swal.fire({
  icon: 'success',
  title: 'Berhasil!',
  text: '{{ session("success") }}',
  timer: 3000,
  showConfirmButton: false
});
@endif
</script>
@endpush


