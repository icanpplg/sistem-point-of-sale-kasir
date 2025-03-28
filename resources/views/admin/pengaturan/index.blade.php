@extends('layouts.app')

@section('title', 'Pengaturan Toko')

@section('content')
  <!-- Header -->
  <header class="text-center mb-8">
    <h1 class="text-4xl font-bold text-gray-800 dark:text-gray-100 flex items-center justify-center mb-6">
      Pengaturan Toko
      <i class="fas fa-store ml-2 text-blue-600"></i>
    </h1>
  </header>

  <!-- Form Pengaturan Toko -->
  <div class="max-w-3xl mx-auto bg-white dark:bg-gray-800 rounded-lg shadow-lg p-6">
    <form id="pengaturanForm" action="#" method="POST" class="space-y-4">
      @csrf
      <!-- Nama Toko -->
      <div>
        <label for="store_name" class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-1">
          Nama Toko:
        </label>
        <input type="text" id="store_name" name="store_name" placeholder="Masukkan nama toko"
          value="{{ old('store_name', $pengaturan->store_name ?? '') }}"
          class="w-full p-2 border border-gray-300 dark:border-gray-600 rounded-md 
                 focus:ring-2 focus:ring-blue-500 focus:border-blue-500 
                 transition duration-300 bg-white dark:bg-gray-700 dark:text-gray-100"
          required>
      </div>

      <!-- Alamat Toko -->
      <div>
        <label for="store_address" class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-1">
          Alamat Toko:
        </label>
        <textarea id="store_address" name="store_address" placeholder="Masukkan alamat toko"
          class="w-full p-2 border border-gray-300 dark:border-gray-600 rounded-md 
                 focus:ring-2 focus:ring-blue-500 focus:border-blue-500 
                 transition duration-300 bg-white dark:bg-gray-700 dark:text-gray-100"
          required>{{ old('store_address', $pengaturan->store_address ?? '') }}</textarea>
      </div>

      <!-- Kontak (HP) -->
      <div>
        <label for="store_contact" class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-1">
          Kontak (HP):
        </label>
        <input type="tel" id="store_contact" name="store_contact" placeholder="Masukkan nomor HP"
          value="{{ old('store_contact', $pengaturan->store_contact ?? '') }}"
          class="w-full p-2 border border-gray-300 dark:border-gray-600 rounded-md 
                 focus:ring-2 focus:ring-blue-500 focus:border-blue-500 
                 transition duration-300 bg-white dark:bg-gray-700 dark:text-gray-100"
          required>
      </div>

      <!-- Nama Pemilik Toko -->
      <div>
        <label for="store_owner" class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-1">
          Nama Pemilik Toko:
        </label>
        <input type="text" id="store_owner" name="store_owner" placeholder="Masukkan nama pemilik toko"
          value="{{ old('store_owner', $pengaturan->store_owner ?? '') }}"
          class="w-full p-2 border border-gray-300 dark:border-gray-600 rounded-md 
                 focus:ring-2 focus:ring-blue-500 focus:border-blue-500 
                 transition duration-300 bg-white dark:bg-gray-700 dark:text-gray-100"
          required>
      </div>

      <!-- Tombol Update -->
      <div class="flex justify-center mt-4">
        <button type="submit"
          class="bg-blue-600 hover:bg-blue-700 text-white px-4 py-2 rounded-md 
                 focus:ring-2 focus:ring-blue-500 focus:outline-none 
                 transition-transform transform hover:scale-105 shadow-md">
          <i class="fas fa-save mr-1"></i> Update Pengaturan Toko
        </button>
      </div>
    </form>
  </div>
@endsection

@push('scripts')
<script>
  document.addEventListener('DOMContentLoaded', () => {
    // Konfigurasi SweetAlert2 untuk notifikasi
    const Toast = Swal.mixin({
      toast: true,
      position: 'top-end',
      showConfirmButton: false,
      timer: 2500,
      timerProgressBar: true,
    });

    const form = document.getElementById('pengaturanForm');
    
    form.addEventListener('submit', function(e) {
      e.preventDefault();
      
      const formData = new FormData(this);

      fetch("{{ route('admin.pengaturan.update') }}", {
        method: 'POST',
        headers: {
          'X-CSRF-TOKEN': '{{ csrf_token() }}',
          'Accept': 'application/json'
        },
        body: formData
      })
      .then(response => response.json())
      .then(data => {
        if (data.success) {
          Toast.fire({
            icon: 'success',
            title: data.message
          });
          // Ambil data terbaru dari respons
          const updated = data.pengaturan;
          // Perbarui nilai form secara langsung
          document.getElementById('store_name').value    = updated.store_name;
          document.getElementById('store_address').value = updated.store_address;
          document.getElementById('store_contact').value = updated.store_contact;
          document.getElementById('store_owner').value   = updated.store_owner;
        } else {
          Toast.fire({
            icon: 'error',
            title: 'Terjadi kesalahan saat memperbarui pengaturan.'
          });
        }
      })
      .catch(error => {
        Toast.fire({
          icon: 'error',
          title: 'Terjadi kesalahan: ' + error
        });
      });
    });
  });
</script>
@endpush
