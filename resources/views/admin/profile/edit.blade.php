@extends('layouts.app')

@section('title', 'Edit Profil')

@section('content')
<header class="text-center mb-8">
  <h1 class="text-4xl font-bold text-gray-800 dark:text-gray-100 flex items-center justify-center mb-6">
    Edit <span class="ml-2 flex items-center">Profil <i class="fas fa-user-edit text-blue-600 ml-2"></i></span>
  </h1>
</header>

<div class="grid grid-cols-1 md:grid-cols-3 gap-6">
  <!-- Form Ganti Password -->
  <div class="bg-white dark:bg-gray-800 rounded-lg shadow-lg p-6">
    <div class="flex items-center mb-4">
      <svg xmlns="http://www.w3.org/2000/svg" class="h-6 w-6 text-blue-600 mr-2" fill="none" viewBox="0 0 24 24" stroke="currentColor">
        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 7a2 2 0 012 2m4 0a6 6 0 01-7.743 5.743L11 17H9v2H7v2H4a1 1 0 01-1-1v-2.586a1 1 0 01.293-.707l5.964-5.964A6 6 0 1121 9z" />
      </svg>
      <h2 class="text-xl font-bold text-gray-800 dark:text-gray-100">Ganti Password</h2>
    </div>
    <form id="update-password-form" action="{{ route('admin.profile.update') }}" method="POST" class="space-y-4">
      @csrf
      @method('PATCH')
      <!-- Username -->
      <div>
        <label for="name" class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-1">Username:</label>
        <input type="text" id="name" name="name" value="{{ old('name', $user->name) }}"
          class="w-full p-2 border border-gray-300 dark:border-gray-600 rounded-md focus:ring-2 focus:ring-blue-500 focus:border-blue-500 transition duration-300 bg-white dark:bg-gray-700 dark:text-gray-100"
          required>
      </div>
      <!-- Password Baru dengan Toggle -->
      <div>
        <label for="password" class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-1">Password Baru:</label>
        <div class="relative">
          <input type="password" id="password" name="password"
            class="w-full p-2 pr-10 border border-gray-300 dark:border-gray-600 rounded-md focus:ring-2 focus:ring-blue-500 focus:border-blue-500 transition duration-300 bg-white dark:bg-gray-700 dark:text-gray-100"
            placeholder="Ubah kata sandi baru">
          <span id="toggle-password" class="absolute inset-y-0 right-3 flex items-center cursor-pointer text-gray-500 hover:text-gray-700 dark:hover:text-gray-300">
            <i class="fas fa-eye"></i>
          </span>
        </div>
      </div>
      <!-- Email -->
      <div>
        <label for="email" class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-1">Alamat Email:</label>
        <div class="relative">
          <span class="absolute inset-y-0 left-0 flex items-center pl-3 text-blue-600">
            <i class="fas fa-envelope"></i>
          </span>
          <input type="email" id="email" name="email" value="{{ old('email', $user->email) }}"
            class="w-full p-2 pl-10 border border-gray-300 dark:border-gray-600 rounded-md focus:ring-2 focus:ring-blue-500 focus:border-blue-500 transition duration-300 bg-white dark:bg-gray-700 dark:text-gray-100"
            required>
        </div>
      </div>
      <!-- Tombol Update -->
      <div class="flex justify-center mt-4">
        <button type="submit"
          class="bg-blue-600 hover:bg-blue-700 text-white px-4 py-2 rounded-md focus:ring-2 focus:ring-blue-500 focus:outline-none transition-transform transform hover:scale-105 shadow-md">
          <i class="fas fa-save mr-1"></i> Update Profil
        </button>
      </div>
    </form>
  </div>

  <!-- Form Update Foto Profil -->
  <div class="bg-white dark:bg-gray-800 rounded-lg shadow-lg p-6">
    <form id="profile-photo-form" action="{{ route('admin.profile.updatePhoto') }}" method="POST" enctype="multipart/form-data">
      @csrf
      @method('PATCH')
      <div class="flex items-center justify-center mb-6">
        <svg xmlns="http://www.w3.org/2000/svg" class="h-6 w-6 text-blue-600 mr-2" fill="none" viewBox="0 0 24 24" stroke="currentColor">
          <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5.121 17.804A13.937 13.937 0 0112 16c2.5 0 4.847.655 6.879 1.804M15 10a3 3 0 11-6 0 3 3 0 016 0zm6 2a9 9 0 11-18 0 9 9 0 0118 0z" />
        </svg>
        <h2 class="text-xl font-bold text-gray-800 dark:text-gray-100">Foto Profil</h2>
      </div>
      <div class="relative mb-6 flex justify-center">
        <img id="profile-picture-preview" 
          src="{{ $user->profile && $user->profile->profile_photo_path ? asset('storage/' . $user->profile->profile_photo_path) : asset('images/default-profile.jpg') }}"
          class="w-32 h-32 rounded-full object-cover shadow-lg border-4 border-blue-100 hover:border-blue-200 transition-all duration-300">
        <label for="profile_picture" 
          class="absolute bottom-2 right-2 bg-blue-600 text-white p-2 rounded-full cursor-pointer hover:bg-blue-700 transition-all duration-300 shadow-md">
          <i class="fas fa-camera text-sm"></i>
        </label>
        <input type="file" id="profile_picture" name="profile_picture" class="hidden" accept="image/*">
      </div>
      <div class="flex justify-center">
        <button type="submit"
          class="bg-blue-600 hover:bg-blue-700 text-white px-6 py-2 rounded-md focus:ring-2 focus:ring-blue-500 focus:outline-none transition-transform transform hover:scale-105 shadow-md flex items-center">
          <i class="fas fa-sync-alt mr-2"></i> Update Foto Profil
        </button>
      </div>
    </form>
  </div>

  <!-- Form Update Informasi Profil -->
  <div class="bg-white dark:bg-gray-800 rounded-lg shadow-lg p-6">
    <form id="update-info-form" action="{{ route('admin.profile.updateInfo') }}" method="POST">
      @csrf
      @method('PATCH')
      <div class="flex items-center mb-4">
        <svg xmlns="http://www.w3.org/2000/svg" class="h-6 w-6 text-blue-600 mr-2" fill="none" viewBox="0 0 24 24" stroke="currentColor">
          <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M16 7a4 4 0 11-8 0 4 4 0 018 0zM12 14a7 7 0 00-7 7h14a7 7 0 00-7-7z" />
        </svg>
        <h2 class="text-xl font-bold text-gray-800 dark:text-gray-100">Informasi Profil</h2>
      </div>
      <!-- Nama -->
      <div class="w-full mb-3">
        <label for="profile_name" class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-1">Nama:</label>
        <input type="text" id="profile_name" name="profile_name" value="{{ old('profile_name', optional($user->profile)->name) }}"
          class="w-full p-2 border border-gray-300 dark:border-gray-600 rounded-md focus:ring-2 focus:ring-blue-500 focus:border-blue-500 transition duration-300 bg-white dark:bg-gray-700 dark:text-gray-100"
          placeholder="Masukkan nama Anda">
      </div>
      <!-- Telepon -->
      <div class="w-full mb-3">
        <label for="profile_phone" class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-1">Telepon:</label>
        <input type="tel" id="profile_phone" name="profile_phone" value="{{ old('profile_phone', optional($user->profile)->phone) }}"
          class="w-full p-2 border border-gray-300 dark:border-gray-600 rounded-md focus:ring-2 focus:ring-blue-500 focus:border-blue-500 transition duration-300 bg-white dark:bg-gray-700 dark:text-gray-100"
          placeholder="Masukkan nomor telepon Anda">
      </div>
      <!-- Alamat -->
      <div class="w-full mb-3">
        <label for="profile_address" class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-1">Alamat:</label>
        <textarea id="profile_address" name="profile_address"
          class="w-full p-2 border border-gray-300 dark:border-gray-600 rounded-md focus:ring-2 focus:ring-blue-500 focus:border-blue-500 transition duration-300 bg-white dark:bg-gray-700 dark:text-gray-100"
          placeholder="Masukkan alamat Anda">{{ old('profile_address', optional($user->profile)->address) }}</textarea>
      </div>
      <div class="flex justify-center mt-4">
        <button type="submit" class="bg-blue-600 hover:bg-blue-700 text-white px-4 py-2 rounded-md focus:ring-2 focus:ring-blue-500 focus:outline-none transition-transform transform hover:scale-105 shadow-md">
          <i class="fas fa-sync-alt mr-1"></i> Update Informasi Profil
        </button>
      </div>
    </form>
  </div>
</div>
@endsection

@push('scripts')
<script>
  document.addEventListener('DOMContentLoaded', () => {
    // Konfigurasi Toast dengan SweetAlert2
    const Toast = Swal.mixin({
      toast: true,
      position: 'top-end',
      showConfirmButton: false,
      timer: 3000,
      timerProgressBar: true,
    });

    async function handleFormSubmit(event) {
      event.preventDefault();
      const form = event.target;
      
      if (!form.checkValidity()) {
        Toast.fire({
          icon: 'error',
          title: 'Harap isi semua field yang diperlukan!'
        });
        return;
      }
      
      const url = form.action;
      const method = form.method.toUpperCase();
      const formData = new FormData(form);
      
      try {
        const response = await fetch(url, {
          method: method,
          headers: {
            'X-Requested-With': 'XMLHttpRequest',
            'Accept': 'application/json'
          },
          body: formData
        });
        
        const data = await response.json();
        
        if (response.ok) {
          Toast.fire({
            icon: 'success',
            title: data.message || 'Perubahan berhasil disimpan.'
          });
          
          // Update preview foto dan foto navbar
          if (form.id === 'profile-photo-form' && data.profile_photo_url) {
            document.getElementById('profile-picture-preview').src = data.profile_photo_url;
            const navbarPhoto = document.getElementById('navbar-profile-photo');
            if (navbarPhoto) {
              navbarPhoto.src = data.profile_photo_url;
            }
          }
          
          // Update nama di navbar
          if (form.id === 'update-info-form' && data.profile_name) {
            const navbarNameElement = document.getElementById('navbar-username');
            if (navbarNameElement) {
              navbarNameElement.textContent = data.profile_name;
            }
          }
        } else {
          Toast.fire({
            icon: 'error',
            title: data.message || 'Terjadi kesalahan.'
          });
        }
      } catch (error) {
        console.error('Error:', error);
        Toast.fire({
          icon: 'error',
          title: 'Terjadi kesalahan pada server.'
        });
      }
    }
    
    // Pasang event listener untuk setiap form
    document.querySelectorAll('form').forEach(form => {
      form.addEventListener('submit', handleFormSubmit);
    });
    
    // Preview foto profil
    const profilePictureInput = document.getElementById('profile_picture');
    if (profilePictureInput) {
      profilePictureInput.addEventListener('change', (event) => {
        const file = event.target.files[0];
        if (file) {
          const reader = new FileReader();
          reader.onload = (e) => {
            document.getElementById('profile-picture-preview').src = e.target.result;
          };
          reader.readAsDataURL(file);
        }
      });
    }
    
    // Toggle Lihat/Sembunyikan Password
    const togglePassword = document.getElementById('toggle-password');
    const passwordInput = document.getElementById('password');
    if (togglePassword && passwordInput) {
      togglePassword.addEventListener('click', () => {
        const type = passwordInput.getAttribute('type') === 'password' ? 'text' : 'password';
        passwordInput.setAttribute('type', type);
        togglePassword.innerHTML = type === 'password' ? '<i class="fas fa-eye"></i>' : '<i class="fas fa-eye-slash"></i>';
      });
    }
  });
</script>
@endpush
