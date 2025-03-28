<!DOCTYPE html>
<html lang="id">
  <head>
    <meta charset="UTF-8" />
    <meta name="viewport" content="width=device-width, initial-scale=1.0" />
    <meta name="csrf-token" content="{{ csrf_token() }}" />
    <title>@yield('title', 'Halaman Admin')</title>

    <!-- Dark Mode Script -->
    <script>
      if (localStorage.getItem('darkMode') === 'true') {
        document.documentElement.classList.add('dark');
      }
    </script>

    <!-- Preconnect for Google Fonts -->
    <link rel="preconnect" href="https://fonts.googleapis.com" />
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin />

    <!-- Vite Assets & Turbo sudah di-load di app.js -->
    @vite(['resources/css/app.css', 'resources/js/app.js'])

    <!-- Google Fonts & Icons -->
    <link
      href="https://fonts.googleapis.com/css2?family=Poppins:wght@300;400;500;600&display=swap"
      rel="stylesheet"
    />
    <link
      href="https://fonts.googleapis.com/icon?family=Material+Icons"
      rel="stylesheet"
    />

    <!-- Chart.js -->
    <script src="https://cdn.jsdelivr.net/npm/chart.js"></script>

    <!-- SweetAlert2 -->
    <link
      rel="stylesheet"
      href="https://cdn.jsdelivr.net/npm/sweetalert2@11/dist/sweetalert2.min.css"
    />
    <script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>

    <!-- Animate CSS -->
    <link
      rel="stylesheet"
      href="https://cdnjs.cloudflare.com/ajax/libs/animate.css/4.1.1/animate.min.css"
    />

    <!-- Font Awesome -->
    <link
      href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0-beta3/css/all.min.css"
      rel="stylesheet"
    />

    <!-- DataTables CSS -->
    <link
      rel="stylesheet"
      href="https://cdn.datatables.net/1.13.4/css/jquery.dataTables.min.css"
    />

    <!-- Toastr CSS -->
    <link
      rel="stylesheet"
      href="https://cdnjs.cloudflare.com/ajax/libs/toastr.js/latest/toastr.min.css"
    />

    <!-- Favicon -->
    <link rel="icon" href="{{ asset('favicon.ico') }}" type="image/x-icon" />

    <!-- Lucide Icons -->
    <script src="https://unpkg.com/lucide@latest" defer></script>

    <!-- Prefetch halaman admin -->
    <link rel="prefetch" href="{{ url('/admin/dashboard/kasir') }}" />
    <link rel="prefetch" href="{{ url('/admin/dashboard/laporanpenjualan') }}" />
    <link rel="prefetch" href="{{ url('/admin/dashboard/stokbarang') }}" />
    <link rel="prefetch" href="{{ url('/admin/dashboard/kategori') }}" />
    <link rel="prefetch" href="{{ url('/admin/dashboard/pengaturan') }}" />

    @stack('styles')
  </head>
  <body class="bg-gray-100 dark:bg-gray-900 text-gray-800 dark:text-gray-200">
    <!-- Header -->
    <header
      class="fixed top-4 left-2 right-2 sm:left-4 sm:right-4 shadow-lg bg-white dark:bg-gray-800 border dark:border-gray-700 rounded-3xl z-20"
    >
      <div class="mx-auto px-4 py-2 flex items-center justify-between">
        <!-- Sidebar Toggle -->
        <div class="flex items-center">
          <button
            id="toggleSidebar"
            aria-label="Toggle Sidebar"
            class="hidden lg:flex p-2 rounded-2xl hover:bg-gray-100 dark:hover:bg-gray-700"
          >
            <span class="material-icons text-3xl lg:text-4xl">chevron_left</span>
          </button>
          <button
            id="hamburger"
            aria-label="Open Mobile Menu"
            class="lg:hidden p-2 rounded-2xl hover:bg-gray-100 dark:hover:bg-gray-700"
          >
            <svg
              xmlns="http://www.w3.org/2000/svg"
              class="h-7 w-7"
              viewBox="0 0 24 24"
              fill="none"
              stroke="currentColor"
              stroke-width="2"
              stroke-linecap="round"
              stroke-linejoin="round"
            >
              <polyline points="15 18 9 12 15 6"></polyline>
            </svg>
          </button>
        </div>

        <!-- Judul -->
        <div class="flex-grow text-center">
          <h2
            class="flex items-center justify-center space-x-2 text-xl sm:text-2xl font-bold"
          >
            <span
              class="flex items-center justify-center w-8 h-8 sm:w-10 sm:h-10 bg-gradient-to-br from-blue-500 to-indigo-600 text-white rounded-full shadow-lg ring-4 ring-indigo-400 hover:rotate-12 hover:scale-110"
            >
              K
            </span>
            <span
              class="tracking-widest uppercase bg-clip-text text-transparent bg-gradient-to-r from-blue-600 to-purple-600 drop-shadow-md"
            >
              aswira
            </span>
          </h2>
        </div>

        <!-- Dark Mode & Profil -->
        <div class="flex items-center space-x-2">
          <button
            id="darkModeToggle"
            aria-label="Toggle Dark Mode"
            class="p-2 rounded-full hover:text-gray-600 dark:hover:text-gray-300"
          >
            <span class="material-icons text-xl sm:text-2xl">dark_mode</span>
          </button>
          <div class="relative">
            @php
              $user = auth()->user();
              $profilePhoto = $user && optional($user->profile)->profile_photo_path
                  ? asset('storage/' . $user->profile->profile_photo_path)
                  : asset('images/default-profile.jpg');
            @endphp
            <button
              id="profileMenu"
              aria-haspopup="true"
              aria-expanded="false"
              aria-label="Open Profile Menu"
              class="flex items-center p-1 sm:p-2 rounded-full focus:outline-none"
            >
              <img
                id="navbar-profile-photo"
                src="{{ $profilePhoto }}"
                alt="Profile Photo"
                class="w-8 h-8 sm:w-9 sm:h-9 rounded-full shadow-md"
              />
              <span
                id="navbar-username"
                class="hidden sm:inline ml-2 text-sm sm:text-base font-medium"
              >
                {{ $user->profile->name ?? $user->name }}
              </span>
              <span class="material-icons ml-1 text-xl sm:text-2xl">expand_more</span>
            </button>
            <div
              id="profileDropdown"
              class="absolute right-0 mt-2 w-40 sm:w-48 bg-white dark:bg-gray-800 shadow-lg rounded-3xl p-2 border dark:border-gray-700 hidden"
            >
              <a
                href="{{ url('admin/dashboard/profile') }}"
                data-turbo-frame="main-content"
                class="group flex items-center px-3 py-2 text-xs sm:text-sm rounded-2xl hover:bg-blue-100 dark:hover:bg-blue-900"
              >
                <span
                  class="material-icons mr-2 text-base group-hover:text-blue-600"
                  >person</span
                >
                <span class="group-hover:text-blue-600">Profil</span>
              </a>
              <form action="{{ route('logout') }}" method="POST" id="logoutForm">
                @csrf
                <button
                  type="button"
                  id="logoutButton"
                  class="group flex items-center px-3 py-2 text-xs sm:text-sm rounded-2xl w-full text-left hover:bg-blue-100 dark:hover:bg-blue-900"
                >
                  <span
                    class="material-icons mr-2 text-base group-hover:text-blue-600"
                    >logout</span
                  >
                  <span class="group-hover:text-blue-600">Keluar</span>
                </button>
              </form>
            </div>
          </div>
        </div>
      </div>
    </header>

    <!-- Sidebar & Konten Utama -->
    <div class="flex h-screen pt-24">
      <!-- Sidebar -->
      <aside
        id="sidebar"
        class="w-64 mx-4 mb-4 bg-white dark:bg-gray-800 border dark:border-gray-700 p-6 shadow-xl rounded-3xl relative"
      >
        <h2 class="text-2xl font-bold border-b pb-3">Menu</h2>
        <nav class="mt-4">
          <ul class="space-y-3">
            <li>
              <a
                href="{{ url('admin/dashboard') }}"
                data-turbo-frame="main-content"
                class="flex items-center gap-3 p-3 rounded-2xl hover:bg-gray-100 dark:hover:bg-gray-700 shadow-sm"
              >
                <span class="material-icons text-2xl text-blue-600">dashboard</span>
                <span>Dashboard</span>
              </a>
            </li>
            <!-- Dropdown Transaksi -->
            <li class="relative">
              <button
                onclick="toggleDropdown('dropdownMenu')"
                class="flex items-center gap-3 p-3 w-full rounded-2xl hover:bg-gray-100 dark:hover:bg-gray-700 shadow-sm"
              >
                <span class="material-icons text-2xl text-blue-600">shopping_cart</span>
                <span>Transaksi</span>
                <span class="material-icons ml-auto">arrow_drop_down</span>
              </button>
              <ul
                id="dropdownMenu"
                class="hidden bg-white dark:bg-gray-800 shadow-md rounded-2xl mt-2"
              >
                <li>
                  <a
                    href="{{ url('/admin/dashboard/kasir') }}"
                    data-turbo-frame="main-content"
                    class="block px-6 py-2 rounded-lg hover:bg-gray-100 dark:hover:bg-gray-700"
                  >
                    Kasir
                  </a>
                </li>
                <li>
                  <a
                    href="{{ url('/admin/dashboard/laporanpenjualan') }}"
                    data-turbo-frame="main-content"
                    class="block px-6 py-2 rounded-lg hover:bg-gray-100 dark:hover:bg-gray-700"
                  >
                    Laporan Penjualan
                  </a>
                </li>
              </ul>
            </li>
            <!-- Dropdown Kelola -->
            <li class="relative">
              <button
                onclick="toggleDropdown('kelolaDropdownMenu')"
                class="flex items-center gap-3 p-3 w-full rounded-2xl hover:bg-gray-100 dark:hover:bg-gray-700 shadow-sm"
              >
                <span class="material-icons text-2xl text-blue-600">manage_accounts</span>
                <span>Kelola</span>
                <span class="material-icons ml-auto">arrow_drop_down</span>
              </button>
              <ul
                id="kelolaDropdownMenu"
                class="hidden bg-white dark:bg-gray-800 shadow-md rounded-2xl mt-2"
              >
                <li>
                  <a
                    href="{{ url('/admin/dashboard/stokbarang') }}"
                    data-turbo-frame="main-content"
                    class="block px-6 py-2 rounded-lg hover:bg-gray-100 dark:hover:bg-gray-700"
                  >
                    Stok Barang
                  </a>
                </li>
                <li>
                  <a
                    href="{{ url('/admin/dashboard/kategori') }}"
                    data-turbo-frame="main-content"
                    class="block px-6 py-2 rounded-lg hover:bg-gray-100 dark:hover:bg-gray-700"
                  >
                    Kategori
                  </a>
                </li>
                <li>
                  <a
                    href="{{ url('/admin/dashboard/satuan') }}"
                    data-turbo-frame="main-content"
                    class="block px-6 py-2 rounded-lg hover:bg-gray-100 dark:hover:bg-gray-700"
                  >
                    Satuan
                  </a>
                </li>
              </ul>
            </li>
            <!-- Pengaturan -->
            <li>
              <a
                href="{{ url('/admin/dashboard/pengaturan') }}"
                data-turbo-frame="main-content"
                class="flex items-center gap-4 p-3 rounded-2xl hover:bg-gray-100 dark:hover:bg-gray-700 shadow-sm"
              >
                <span class="material-icons text-2xl text-blue-600">settings</span>
                <span>Pengaturan</span>
              </a>
            </li>
          </ul>
        </nav>
        <!-- Footer Sidebar -->
        <div
          class="absolute bottom-2 left-6 right-6 text-center text-gray-500 text-sm"
        >
          <hr class="mb-2 border-gray-300 dark:border-gray-600" />
          &copy; {{ date('Y') }} Kaswira Kasir Wirausaha. All rights reserved.
        </div>
      </aside>

      <!-- Konten Utama (Turbo Frame) -->
      <turbo-frame
        id="main-content"
        class="flex-1 p-8 overflow-y-auto dark:bg-gray-900"
      >
        @yield('content')
      </turbo-frame>
    </div>

    <!-- jQuery & DataTables JS -->
    <script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
    <script src="https://cdn.datatables.net/1.13.4/js/jquery.dataTables.min.js"></script>

    <!-- Toastr JS -->
    <script src="https://cdnjs.cloudflare.com/ajax/libs/toastr.js/latest/toastr.min.js"></script>

    <!-- Custom Scripts -->
    <script defer>
      document.addEventListener("DOMContentLoaded", () => {
        // Debug Turbo Events
        document.addEventListener("turbo:click", (e) => console.log("Turbo click", e));
        document.addEventListener("turbo:before-fetch-request", (e) =>
          console.log("Before fetch", e)
        );
        document.addEventListener("turbo:load", (e) => console.log("Turbo load", e));

        // Toggle Sidebar (Desktop)
        const sidebar = document.getElementById("sidebar");
        const toggleSidebarBtn = document.getElementById("toggleSidebar");
        const chevronIcon = toggleSidebarBtn.querySelector("span");
        toggleSidebarBtn.addEventListener("click", () => {
          sidebar.classList.toggle("hidden");
          chevronIcon.textContent = sidebar.classList.contains("hidden")
            ? "chevron_right"
            : "chevron_left";
        });

        // Toggle Sidebar (Mobile)
        document.getElementById("hamburger").addEventListener("click", () => {
          sidebar.classList.toggle("hidden");
        });

        // Dropdown Toggle
        window.toggleDropdown = (menuId) => {
          const dropdowns = ["dropdownMenu", "kelolaDropdownMenu"];
          dropdowns.forEach((id) => {
            if (id !== menuId)
              document.getElementById(id).classList.add("hidden");
          });
          document.getElementById(menuId).classList.toggle("hidden");
        };

        // Profile Dropdown Toggle
        document.getElementById("profileMenu").addEventListener("click", () => {
          document.getElementById("profileDropdown").classList.toggle("hidden");
        });

        // Logout AJAX dengan Turbo (tanpa reload penuh)
        document.getElementById("logoutButton").addEventListener("click", function () {
          Swal.fire({
            title: "Keluar dari Akun?",
            text: "Anda yakin ingin keluar? Semua sesi akan ditutup.",
            iconHtml: `<svg xmlns="http://www.w3.org/2000/svg" class="h-16 w-16 text-blue-600" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                          <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17 16l4-4m0 0l-4-4m4 4H7m6 4v1a2 2 0 01-2 2H5a2 2 0 01-2-2V7a2 2 0 012-2h6a2 2 0 012 2v1"/>
                       </svg>`,
            showCancelButton: true,
            confirmButtonColor: "#2563eb",
            cancelButtonColor: "#9ca3af",
            confirmButtonText: "Ya, Keluar!",
            cancelButtonText: "Batal",
            customClass: {
              popup: "rounded-xl shadow-2xl p-6",
              title: "text-2xl font-semibold text-gray-800",
              htmlContainer: "text-gray-600 text-sm",
              confirmButton:
                "bg-blue-600 hover:bg-blue-700 text-white font-bold py-2 px-4 rounded-lg",
              cancelButton:
                "bg-gray-300 hover:bg-gray-400 text-gray-800 font-semibold py-2 px-4 rounded-lg",
            },
            background: "#f9fafb",
            backdrop: "rgba(0, 0, 0, 0.5)",
            allowOutsideClick: false,
            allowEscapeKey: true,
            showClass: { popup: "animate__animated animate__fadeInDown" },
            hideClass: { popup: "animate__animated animate__fadeOutUp" },
          }).then((result) => {
            if (result.isConfirmed) {
              const btn = this;
              btn.disabled = true;
              btn.innerHTML = `<svg class="animate-spin h-5 w-5 inline-block mr-2" fill="none" viewBox="0 0 24 24">
                                  <circle class="opacity-25" cx="12" cy="12" r="10" stroke="currentColor" stroke-width="4"></circle>
                                  <path class="opacity-75" fill="currentColor" d="M4 12a8 8 0 018-8V0C5.373 0 0 5.373 0 12h4z"></path>
                                </svg>
                                Loading...`;
              fetch("{{ route('logout') }}", {
                method: "POST",
                headers: {
                  "X-CSRF-TOKEN": "{{ csrf_token() }}",
                  Accept: "application/json",
                },
              })
                .then((response) => response.json())
                .then((data) => {
                  if (data.success) {
                    Turbo.visit(data.redirect);
                  }
                })
                .catch((error) => {
                  console.error("Logout error:", error);
                  btn.disabled = false;
                  btn.innerHTML = `<span class="material-icons mr-2">logout</span><span>Keluar</span>`;
                  Swal.fire("Error", "Terjadi kesalahan saat logout.", "error");
                });
            }
          });
        });

        // Dark Mode Toggle
        const darkModeToggle = document.getElementById("darkModeToggle");
        const darkModeIcon = darkModeToggle.querySelector("span");
        darkModeIcon.textContent = document.documentElement.classList.contains("dark")
          ? "light_mode"
          : "dark_mode";
        darkModeToggle.addEventListener("click", () => {
          document.documentElement.classList.toggle("dark");
          darkModeIcon.textContent = document.documentElement.classList.contains("dark")
            ? "light_mode"
            : "dark_mode";
          localStorage.setItem("darkMode", document.documentElement.classList.contains("dark") ? "true" : "false");
        });
      });
    </script>
    @stack("scripts")
  </body>
</html>
