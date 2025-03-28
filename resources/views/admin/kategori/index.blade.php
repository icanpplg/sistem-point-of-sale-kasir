@extends('layouts.app')

@section('title', 'Manajemen Kategori')

@section('content')
  <!-- Header -->
  <header class="mb-10">
    <h1 class="text-4xl font-bold text-gray-800 dark:text-gray-100">Manajemen Kategori Barang</h1>
    <p class="text-gray-600 dark:text-gray-300 mt-2 text-lg">
      Kelola kategori barang dengan mudah
    </p>
  </header>
  
  <!-- Section Kategori -->
  <section id="kategori" class="mb-10">
    <div class="flex flex-col md:flex-row md:items-center md:justify-between">
      <h2 class="text-2xl font-semibold text-gray-800 dark:text-gray-100 mb-4 md:mb-0">
        Daftar Kategori
      </h2>
      <div class="flex flex-col sm:flex-row items-stretch sm:items-center gap-4">
        <!-- Input Kategori -->
        <div class="flex-1">
          <input
            id="kategoriInput"
            type="text"
            placeholder="Masukkan Kategori..."
            class="w-full border border-gray-300 dark:border-gray-600 rounded-lg py-2 px-4 focus:outline-none focus:ring-2 focus:ring-blue-600 transition duration-200 shadow-sm bg-white dark:bg-gray-700 dark:text-gray-100"
          />
        </div>
        <!-- Tombol tambah kategori -->
        <button
          id="tambahKategoriButton"
          class="flex items-center gap-2 bg-blue-600 text-white py-2 px-5 rounded-lg hover:bg-blue-700 transition duration-200 focus:outline-none active:scale-95 shadow-lg"
        >
          <span class="material-icons">add</span>
          Tambah Kategori
        </button>
      </div>
    </div>
    
    <!-- Tabel Kategori -->
    <div class="bg-white dark:bg-gray-800 shadow-lg rounded-lg overflow-x-auto mt-6">
      <table id="kategoriTable" class="w-full table-auto">
        <thead class="bg-blue-600 dark:bg-blue-700">
          <tr>
            <th class="px-4 py-3 text-left text-xs font-medium text-white uppercase tracking-wider">No</th>
            <th class="px-4 py-3 text-left text-xs font-medium text-white uppercase tracking-wider">Nama Kategori</th>
            <th class="px-4 py-3 text-left text-xs font-medium text-white uppercase tracking-wider">Tanggal Dibuat</th>
            <th class="px-4 py-3 text-left text-xs font-medium text-white uppercase tracking-wider">Tanggal Di Update</th>
            <th class="px-4 py-3 text-left text-xs font-medium text-white uppercase tracking-wider">Aksi</th>
          </tr>
        </thead>
        <tbody class="divide-y divide-gray-200 dark:divide-gray-600">
          @foreach($categories as $index => $category)
            <tr data-id="{{ $category->id }}" class="hover:bg-gray-50 dark:hover:bg-gray-600 transition duration-150">
              <td class="px-4 py-4 text-gray-700 dark:text-gray-200">
                {{ $index + 1 }}
              </td>
              <!-- Tambahkan class category-name untuk update via AJAX -->
              <td class="px-4 py-4 text-gray-700 dark:text-gray-200 category-name">
                {{ $category->name }}
              </td>
              <td class="px-4 py-4 text-gray-700 dark:text-gray-200">
                {{ $category->created_at->format('Y-m-d') }}
              </td>
              <td class="px-4 py-4 text-gray-700 dark:text-gray-200">
                {{ $category->updated_at->format('Y-m-d') }}
              </td>
              <td class="px-4 py-4">
                <div class="flex flex-col sm:flex-row space-y-2 sm:space-y-0 sm:space-x-4">
                  <a href="#"
                     class="editCategoryButton block text-center text-blue-600 hover:text-blue-800 font-medium transition duration-150"
                     data-id="{{ $category->id }}"
                     data-name="{{ $category->name }}">
                    Edit
                  </a>
                  <a href="#"
                     class="deleteCategoryButton block text-center text-red-600 hover:text-red-800 font-medium transition duration-150"
                     data-id="{{ $category->id }}">
                    Hapus
                  </a>
                </div>
              </td>
            </tr>
          @endforeach
          <!-- Baris kategori baru akan ditambahkan secara dinamis -->
        </tbody>
      </table>
    </div>
  </section>
@endsection

@push('scripts')
<script defer>
  document.addEventListener('DOMContentLoaded', function() {
    // Ambil CSRF token
    const csrfToken = document.querySelector('meta[name="csrf-token"]').getAttribute('content');
    // Karena route kategori berada di dalam group admin (prefix: admin/dashboard/kategori)
    const baseUrl = "{{ url('admin/dashboard/kategori') }}";
    
    // Inisialisasi DataTable dengan pilihan show entries dan default 5 data per halaman
    const table = $('#kategoriTable').DataTable({
      dom: '<"flex flex-col sm:flex-row items-center justify-between p-4"lf>rt<"flex flex-col sm:flex-row items-center justify-between p-4"ip>',
      language: {
        search: "",
        searchPlaceholder: "Cari kategori..."
      },
      pageLength: 5,
      lengthMenu: [ [5, 10, 25, 50, -1], [5, 10, 25, 50, "All"] ]
    });
    
    // Tambahkan ikon pencarian ke input DataTables
    const $dataTableFilter = $('.dataTables_filter');
    $dataTableFilter.find('input').addClass('pl-10');
    $dataTableFilter.prepend('<span class="material-icons text-blue-600">search</span>');
    
    // ---------------------------
    // AJAX Create Kategori
    // ---------------------------
    $('#tambahKategoriButton').on('click', function(e) {
      e.preventDefault();
      const kategoriName = $('#kategoriInput').val().trim();
      if (!kategoriName) {
        Swal.fire({
          icon: 'warning',
          title: 'Oops...',
          text: 'Nama kategori tidak boleh kosong!'
        });
        return;
      }
      $.ajax({
        url: "{{ route('admin.kategori.store') }}",
        method: 'POST',
        headers: { 'Accept': 'application/json' },
        data: { name: kategoriName, _token: csrfToken },
        success: function(response) {
          Swal.fire({
            icon: 'success',
            title: 'Berhasil',
            text: response.success,
            timer: 2000,
            showConfirmButton: false
          });
          $('#kategoriInput').val('');
          const newIndex = table.data().count() + 1;
          // Ambil tanggal dari response atau gunakan tanggal hari ini
          const currentDate = response.category.created_at 
                              ? response.category.created_at.substr(0,10) 
                              : new Date().toISOString().split('T')[0];
          const newRow = table.row.add([
            newIndex,
            response.category.name,
            currentDate,
            currentDate,
            '<div class="flex space-x-4">' +
              '<a href="#" class="editCategoryButton text-blue-600 hover:text-blue-800 font-medium transition duration-150" data-id="'+ response.category.id +'" data-name="'+ response.category.name +'">Edit</a>' +
              '<a href="#" class="deleteCategoryButton text-red-600 hover:text-red-800 font-medium transition duration-150" data-id="'+ response.category.id +'">Hapus</a>' +
            '</div>'
          ]).draw().node();
          $(newRow).attr('data-id', response.category.id);
          // Tambahkan class pada kolom nama kategori
          $(newRow).find('td').eq(1).addClass('category-name');
        },
        error: function(xhr) {
          console.error("Create error: ", xhr.responseText);
          Swal.fire({
            icon: 'error',
            title: 'Error',
            text: 'Gagal menambahkan kategori!'
          });
        }
      });
    });
    
    // ---------------------------
    // AJAX Edit Kategori (tanpa refresh halaman)
    // ---------------------------
    $('#kategoriTable').on('click', '.editCategoryButton', function (e) {
      e.preventDefault();
      const $this = $(this);
      const id = $this.data('id');
      const currentName = $this.data('name');
    
      Swal.fire({
        title: 'Edit Kategori',
        input: 'text',
        inputValue: currentName,
        inputPlaceholder: 'Masukkan nama kategori...',
        showCancelButton: true,
        confirmButtonText: 'Update',
        cancelButtonText: 'Batal',
        customClass: {
          input: 'border border-blue-600 rounded focus:outline-none focus:ring-2 focus:ring-blue-600 focus:border-transparent p-2',
          confirmButton: 'bg-blue-600 hover:bg-blue-700 text-white font-bold py-2 px-4 rounded shadow-md',
          cancelButton: 'bg-gray-300 hover:bg-gray-400 text-gray-800 font-bold py-2 px-4 rounded shadow-md'
        },
        inputValidator: (value) => {
          if (!value) {
            return 'Nama kategori tidak boleh kosong!';
          }
          return null;
        }
      }).then((result) => {
        if (result.isConfirmed) {
          $.ajax({
            url: baseUrl + '/' + id,
            method: 'POST', // Menggunakan POST dengan override _method=PUT
            headers: { 'Accept': 'application/json' },
            data: { name: result.value, _token: csrfToken, _method: 'PUT' },
            success: function (response) {
              Swal.fire({
                icon: 'success',
                title: 'Berhasil',
                text: response.success,
                timer: 2000,
                showConfirmButton: false
              });
              // Cari baris yang bersangkutan
              const $row = $this.closest('tr');
              // Update nama kategori pada kolom yang bersangkutan
              $row.find('.category-name').text(response.category.name);
              // Perbarui data attribute agar edit selanjutnya mendapatkan nilai terbaru
              $this.attr('data-name', response.category.name);
            },
            error: function (xhr) {
              console.error("Update error: ", xhr.responseText);
              let errorMsg = 'Gagal memperbarui kategori!';
              if (xhr.responseJSON && xhr.responseJSON.error) {
                errorMsg += ' ' + xhr.responseJSON.error;
              }
              Swal.fire({
                icon: 'error',
                title: 'Error',
                text: errorMsg
              });
            }
          });
        }
      });
    });
    
    // ---------------------------
    // AJAX Delete Kategori
    // ---------------------------
    $('#kategoriTable').on('click', '.deleteCategoryButton', function (e) {
      e.preventDefault();
      const $this = $(this);
      const id = $this.data('id');
      const $row = $this.closest('tr');
    
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
            url: baseUrl + '/' + id,
            method: 'POST', // Menggunakan POST dengan override _method=DELETE
            headers: { 'Accept': 'application/json' },
            data: { _token: csrfToken, _method: 'DELETE' },
            success: function (response) {
              Swal.fire({
                icon: 'success',
                title: 'Berhasil!',
                text: response.success,
                timer: 2000,
                showConfirmButton: false
              });
              // Hapus baris dari DataTable dan redraw
              table.row($row).remove().draw();
            },
            error: function (xhr) {
              console.error("Delete error: ", xhr.responseText);
              let errorMsg = 'Gagal menghapus kategori!';
              if (xhr.responseJSON && xhr.responseJSON.error) {
                errorMsg += ' ' + xhr.responseJSON.error;
              }
              Swal.fire({
                icon: 'error',
                title: 'Error!',
                text: errorMsg
              });
            }
          });
        }
      });
    });
    
    // ---------------------------
    // Fokus pada input kategori saat tombol tambah ditekan
    // ---------------------------
    $('#tambahKategoriButton').on('click', function() {
      $('#kategoriInput').focus();
    });
    
  });
</script>
@endpush
