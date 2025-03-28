<!DOCTYPE html>
<html lang="id">
<head>
  <meta charset="UTF-8">
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <title>Admin Login Kaswira</title>
 
  <!-- Vite: Memuat file CSS dan JS yang sudah dikompilasi -->
  @vite(['resources/css/app.css', 'resources/js/app.js'])
  <script src="https://unpkg.com/lucide@latest"></script>
  <style>
    .hover-animate:hover {
      transform: scale(1.03);
      box-shadow: 0 8px 20px rgba(0, 0, 0, 0.1);
    }
    .input-focus:focus {
      border-color: #4A90E2;
      box-shadow: 0 0 0 1px #4A90E2;
    }
  </style>
</head>
<body class="bg-gradient-to-r from-gray-100 via-gray-200 to-white flex items-center justify-center min-h-screen px-4 sm:px-6 lg:px-8">
  
  <div class="bg-white shadow-xl rounded-2xl p-6 sm:p-10 max-w-sm sm:max-w-md w-full">
    <h2 class="text-2xl sm:text-3xl font-semibold text-center text-gray-800 mb-4 sm:mb-6">Login Admin</h2>
    <p class="text-center text-gray-500 mb-6 sm:mb-8">Dapatkan kontrol penuh atas toko Anda.</p>

    <form method="POST" action="{{ route('login') }}" class="space-y-4 sm:space-y-6" id="loginForm">
      @csrf 

      <div>
        <label for="name" class="block text-sm font-medium text-gray-600">Username</label>
        <div class="relative">
          <input type="text" id="name" name="name" value="{{ old('name') }}" required class="input-focus mt-1 block w-full pl-10 pr-4 py-2 sm:py-3 bg-gray-100 border border-gray-300 rounded-lg shadow-sm focus:outline-none focus:ring-2 focus:ring-blue-300 transition duration-200" placeholder="Masukkan username admin">
          <i data-lucide="user" class="absolute left-3 top-1/2 transform -translate-y-1/2 text-gray-400"></i>
        </div>
        @error('name')
          <span class="text-red-500 text-sm mt-1">{{ $message }}</span>
        @enderror
      </div>

      <div>
        <label for="password" class="block text-sm font-medium text-gray-600">Password</label>
        <div class="relative">
          <input type="password" id="password" name="password" required class="input-focus mt-1 block w-full pl-10 pr-10 py-2 sm:py-3 bg-gray-100 border border-gray-300 rounded-lg shadow-sm focus:outline-none focus:ring-2 focus:ring-blue-300 transition duration-200" placeholder="Masukkan password admin">
          <i data-lucide="lock" class="absolute left-3 top-1/2 transform -translate-y-1/2 text-gray-400"></i>
          <button type="button" onclick="togglePassword()" class="absolute right-3 top-1/2 transform -translate-y-1/2 text-gray-400 focus:outline-none">
            <i id="togglePasswordIcon" data-lucide="eye"></i>
          </button>
        </div>
        @error('password')
          <span class="text-red-500 text-sm mt-1">{{ $message }}</span>
        @enderror
      </div>

      <div class="flex items-center justify-between text-sm">
        <div class="flex items-center">
          <input type="checkbox" id="remember" name="remember" class="rounded border-gray-300 text-blue-600 shadow-sm focus:ring-blue-500 focus:border-blue-500">
          <label for="remember" class="text-gray-600 ml-2">Ingat saya</label>
        </div>
      </div>

      <button type="submit" class="w-full py-2 sm:py-3 px-4 bg-blue-600 text-white rounded-lg shadow-md hover:bg-blue-700 transition duration-200" id="submitButton">Login</button>
    </form>

    <!-- Tempat untuk menampilkan pesan error -->
    <div id="errorMessage" class="mt-4 text-center text-red-500"></div>

    @if(session('status'))
      <p class="text-green-500 text-sm mt-4 text-center">{{ session('status') }}</p>
    @endif
  </div>

  <script>
    // Inisialisasi Lucide Icons
    lucide.createIcons();

    // Toggle password visibility
    function togglePassword() {
      const passwordField = document.getElementById('password');
      const toggleIcon = document.getElementById('togglePasswordIcon');
      if (passwordField.type === 'password') {
        passwordField.type = 'text';
        toggleIcon.setAttribute('data-lucide', 'eye-off');
      } else {
        passwordField.type = 'password';
        toggleIcon.setAttribute('data-lucide', 'eye');
      }
      lucide.createIcons();
    }

    // Proses login AJAX tanpa reload halaman
    const loginForm = document.getElementById('loginForm');
    const errorMessage = document.getElementById('errorMessage');

    loginForm.addEventListener('submit', function(e) {
      e.preventDefault();
      errorMessage.textContent = '';
      const formData = new FormData(loginForm);
      const submitButton = document.getElementById('submitButton');
      submitButton.disabled = true;
      submitButton.innerText = 'Loading...';

      fetch(loginForm.action, {
        method: 'POST',
        headers: {
          'Accept': 'application/json'
        },
        body: formData
      })
      .then(response => {
        submitButton.disabled = false;
        submitButton.innerText = 'Login';
        if (!response.ok) {
          return response.json().then(data => {
            throw new Error(data.message || 'Login gagal');
          });
        }
        return response.json();
      })
      .then(data => {
        if (data.success) {
          // Jika Turbo tersedia, gunakan Turbo.visit untuk navigasi tanpa reload penuh
          if(window.Turbo) {
            Turbo.visit(data.redirect);
          } else {
            window.location.href = data.redirect || '/admin/dashboard';
          }
        }
      })
      .catch(error => {
        errorMessage.textContent = error.message;
      });
    });
  </script>
</body>
</html>
