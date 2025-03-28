@extends('layouts.app')

@section('title', 'Halaman Kasir - Admin')

@section('content')
  <!-- Header -->
  <header class="mb-6 md:mb-8">
    <div class="flex flex-col md:flex-row justify-between items-start md:items-center gap-4">
      <div>
        <h1 class="text-2xl md:text-4xl font-bold text-gray-800 dark:text-white">Transaksi Cerdas</h1>
        <p class="text-gray-600 dark:text-gray-300 text-base md:text-lg">Pembayaran cepat, mudah, dan tanpa repot</p>
      </div>
      <div class="w-full md:w-auto text-left md:text-right p-4 bg-white dark:bg-[#2C3442] shadow-md rounded-lg border border-blue-600">
        <table class="w-full md:w-auto table-auto border-collapse">
          <tr class="bg-blue-600 text-white">
            <td class="px-4 py-2 font-bold border border-blue-600">Hari</td>
            <td class="px-4 py-2 border border-blue-600" id="currentDay"></td>
          </tr>
          <tr>
            <td class="px-4 py-2 font-bold border border-blue-600 text-blue-600">Tanggal</td>
            <td class="px-4 py-2 border border-blue-600" id="currentDate"></td>
          </tr>
          <tr>
            <td class="px-4 py-2 font-bold border border-blue-600 text-blue-600">Waktu</td>
            <td class="px-4 py-2 border border-blue-600" id="currentTime"></td>
          </tr>
        </table>
      </div>
    </div>
  </header>

  <!-- Bagian Transaksi Kasir -->
  <section id="kasir">
    <h2 class="text-xl font-semibold text-gray-800 dark:text-white mb-4">Transaksi Kasir</h2>

    <div class="grid grid-cols-1 lg:grid-cols-2 gap-4">
      <!-- Daftar Barang -->
      <div class="bg-white dark:bg-gray-800 rounded-xl shadow p-4">
  <!-- Flex container untuk judul & tombol -->
  <div class="flex items-center justify-between mb-3">
          <h3 class="text-lg font-semibold text-gray-800 dark:text-white">Daftar Barang</h3>
          <button id="downloadPdfBtn" data-url="{{ route('admin.kasir.barcodepdf') }}" class="bg-red-500 hover:bg-red-600 text-white py-2 px-3 rounded text-sm flex items-center">
  <span class="material-icons mr-1">picture_as_pdf</span> Unduh PDF Barcode
</button>

        </div>

        <!-- Tabel Produk -->
        <div class="overflow-x-auto">
          <table id="barangTable" class="modern-table min-w-full border border-gray-200 dark:border-[#39455A] rounded-md text-sm">
            <thead>
              <tr>
                <th class="px-2 py-1 text-left">Kode Barang</th>
                <th class="px-2 py-1 text-left">Barcode</th>
                <th class="px-2 py-1 text-left">Nama Produk</th>
                <th class="px-2 py-1 text-left">Harga Beli</th>
                <th class="px-2 py-1 text-left">Harga Jual</th>
                <th class="px-2 py-1 text-left">Aksi</th>
              </tr>
            </thead>
            <tbody id="barangTableBody">
              @foreach ($barangs as $barang)
                <tr class="border-b border-gray-200 dark:border-[#39455A] hover:bg-gray-50 dark:hover:bg-[#313A4C]">
                  <td class="px-2 py-1" data-code="{{ $barang->kode_barang }}">{{ $barang->kode_barang }}</td>
                  <td class="px-2 py-1">
                    <div class="barcode dark:filter dark:invert">
                      {!! DNS1D::getBarcodeHTML($barang->kode_barang, 'C128', 2, 50) !!}
                    </div>
                  </td>
                  <td class="px-2 py-1" data-name="{{ $barang->nama_produk }}">{{ $barang->nama_produk }}</td>
                  <td class="px-2 py-1">
                    Rp {{ number_format($barang->harga_beli, 0, ',', '.') }}
                  </td>
                  <td class="px-2 py-1" data-price="{{ $barang->harga_jual }}">
                    Rp {{ number_format($barang->harga_jual, 0, ',', '.') }}
                  </td>
                  <td class="px-2 py-1">
                    <button class="text-blue-500 dark:text-blue-400 hover:text-blue-700 transition duration-300 add-to-cart-btn">
                      <span class="material-icons text-base">add_shopping_cart</span>
                    </button>
                  </td>
                </tr>
              @endforeach
            </tbody>
          </table>
        </div>
      </div>



<!-- Keranjang Belanja & Form Pembayaran -->
<div class="space-y-4">
  <!-- Keranjang Belanja -->
  <div class="bg-white dark:bg-gray-800 rounded-xl shadow p-4">
    <div class="flex justify-between items-center mb-3">
      <h3 class="text-lg font-semibold text-gray-800 dark:text-white">Keranjang Belanja</h3>
      <button onclick="confirmClearCart()" 
              class="bg-red-500 text-white py-1 px-3 rounded-md hover:bg-red-600 transition flex items-center text-sm">
        <span class="material-icons mr-1">restore</span>Reset Keranjang
      </button>
    </div>
    <div class="overflow-x-auto">
      <!-- Tambahkan id="cartTable" pada tabel -->
      <table id="cartTable" class="min-w-full border border-gray-200 dark:border-[#39455A] rounded-md text-sm">
        <thead>
          <tr class="bg-gray-50 dark:bg-[#39455A] text-gray-700 dark:text-gray-200">
            <th class="px-2 py-1 text-left">No</th>
            <th class="px-2 py-1 text-left">Nama Produk</th>
            <th class="px-2 py-1 text-left">Jumlah</th>
            <th class="px-2 py-1 text-left">Total</th>
            <th class="px-2 py-1 text-left">Kasir</th>
            <th class="px-2 py-1 text-left">Aksi</th>
          </tr>
        </thead>
        <tbody>
          <!-- Baris akan dikelola oleh DataTables -->
        </tbody>
      </table>
    </div>
  </div>



        <!-- Form Pembayaran -->
        <div class="bg-white dark:bg-gray-800 rounded-xl shadow p-4">
          <h3 class="text-lg font-semibold text-gray-800 dark:text-white mb-3">Form Pembayaran</h3>
          <div class="mb-3">
            <label for="transactionDate" class="block text-gray-700 dark:text-gray-300 text-sm mb-1">Tanggal Transaksi</label>
            <input type="text" id="transactionDate"
              class="w-full p-2 border border-gray-300 dark:border-[#39455A] rounded-md bg-gray-50 dark:bg-[#39455A] text-sm font-semibold text-gray-800 dark:text-gray-200"
              readonly/>
          </div>
          <div class="mb-3">
            <label for="total" class="block text-gray-700 dark:text-gray-300 text-sm mb-1">Total Pembayaran</label>
            <input type="text" id="total" value="Rp 0"
              class="w-full p-2 border border-gray-300 dark:border-[#39455A] rounded-md bg-gray-50 dark:bg-[#39455A] text-sm font-semibold text-gray-800 dark:text-gray-200"
              readonly/>
          </div>
          <div class="mb-3">
            <label for="payment" class="block text-gray-700 dark:text-gray-300 text-sm mb-1">Pembayaran</label>
            <input type="number" id="payment"
              class="w-full p-2 border border-gray-300 dark:border-[#39455A] rounded-md focus:outline-none focus:ring-2 focus:ring-blue-500 text-sm bg-white dark:bg-[#39455A] text-gray-800 dark:text-gray-200"
              placeholder="Masukkan jumlah pembayaran"/>
          </div>
          <div class="mb-3">
            <label for="change" class="block text-gray-700 dark:text-gray-300 text-sm mb-1">Kembalian</label>
            <input type="text" id="change"
              class="w-full p-2 border border-gray-300 dark:border-[#39455A] rounded-md bg-gray-50 dark:bg-[#39455A] text-sm font-semibold text-gray-800 dark:text-gray-200"
              readonly/>
          </div>
          <div class="flex justify-end gap-2">
            <button onclick="printReceipt()"
              class="bg-blue-500 text-white py-2 px-4 rounded-md hover:bg-blue-600 transition flex items-center text-sm">
              <span class="material-icons mr-1">print</span>Cetak Struk
            </button>
            <button onclick="saveTransaction()"
              class="bg-green-500 text-white py-2 px-4 rounded-md hover:bg-green-600 transition flex items-center text-sm">
              <span class="material-icons mr-1">save</span>Simpan
            </button>
          </div>
        </div>
      </div>
    </div>
  </section>
@endsection
@push('scripts')
<script>
$(document).ready(function() {

  /* -------------------------------------------------------------------------
   | 1. Variabel Global & Fungsi Utilitas
   * ----------------------------------------------------------------------- */
  
  // Nama kasir (diambil dari auth user)
  var kasirName = "{{ auth()->user()->profile->name ?? auth()->user()->name }}";

  // Ambil data toko dari pengaturan (dari controller)
  let storeAddress = {!! json_encode($pengaturan->store_address ?? '') !!};
  let storeContact = {!! json_encode($pengaturan->store_contact ?? '') !!};
  let storeOwner   = {!! json_encode($pengaturan->store_owner ?? '') !!};
  let storeName    = {!! json_encode($pengaturan->store_name ?? '') !!};

  // Variabel keranjang
  let cartItems = [];
  let transactionDate = null;

  // Fungsi format Rupiah
  function formatRupiah(number) {
    return "Rp " + Number(number).toLocaleString("id-ID");
  }

  // Update Hari, Tanggal, dan Waktu secara real-time
  function updateDateTime() {
    const now = new Date();
    const optionsDate = { year: "numeric", month: "long", day: "numeric" };
    const optionsDay = { weekday: "long" };
    const optionsTime = { hour: "2-digit", minute: "2-digit", second: "2-digit" };
    document.getElementById("currentDate").innerText = now.toLocaleDateString("id-ID", optionsDate);
    document.getElementById("currentDay").innerText = now.toLocaleDateString("id-ID", optionsDay);
    document.getElementById("currentTime").innerText = now.toLocaleTimeString("id-ID", optionsTime);
  }
  updateDateTime();
  setInterval(updateDateTime, 1000);

  /* -------------------------------------------------------------------------
   | 2. Inisialisasi DataTables untuk Keranjang Belanja
   * ----------------------------------------------------------------------- */
  var cartTable = $('#cartTable').DataTable({
    pageLength: 5,
    lengthMenu: [5, 10, 25, 50, 100],
    searching: true,
    info: true,
    ordering: false,
    autoWidth: false,
    // Otomatis menomori kolom pertama (index 0)
    fnRowCallback: function(nRow, aData, iDisplayIndex, iDisplayIndexFull) {
      $('td:eq(0)', nRow).html(iDisplayIndexFull + 1);
    }
  });

  /* -------------------------------------------------------------------------
   | 3. Fungsi Load & Save Cart (LocalStorage)
   * ----------------------------------------------------------------------- */
  // Simpan keranjang ke localStorage
  function saveCart() {
    const itemsToSave = cartItems.map(item => ({
      code: item.code,
      name: item.name,
      quantity: item.quantity,
      unitPrice: item.unitPrice,
      total: item.total
    }));
    localStorage.setItem("cartItems", JSON.stringify(itemsToSave));
    if (transactionDate) {
      localStorage.setItem("transactionDate", transactionDate.toISOString());
    }
  }

  // Muat keranjang dari localStorage
  function loadCart() {
    const saved = localStorage.getItem("cartItems");
    if (saved) {
      const items = JSON.parse(saved);
      cartItems = [];
      cartTable.clear().draw(); // Bersihkan DataTables keranjang

      // Tambahkan item yang disimpan ke tabel keranjang
      items.forEach(item => {
        addToCart(item.name, item.unitPrice, item.quantity, item.code, true);
      });

      // Pulihkan tanggal transaksi jika ada
      const transDate = localStorage.getItem("transactionDate");
      if (transDate) {
        transactionDate = new Date(transDate);
        document.getElementById("transactionDate").value = transactionDate.toLocaleString("id-ID", {
          weekday: "long",
          day: "2-digit",
          month: "long",
          year: "numeric",
          hour: "2-digit",
          minute: "2-digit"
        });
      }
      updateTotal();
    }
  }

  /* -------------------------------------------------------------------------
   | 4. Fungsi Menambah, Mengubah, dan Menghapus Item di Keranjang
   * ----------------------------------------------------------------------- */
  // Menambahkan baris ke keranjang belanja
  function addToCart(name, price, quantity = 1, code = null, fromStorage = false) {
    // Jika belum ada tanggal transaksi, set sekarang
    if (!transactionDate) {
      transactionDate = new Date();
      document.getElementById("transactionDate").value = transactionDate.toLocaleString("id-ID", {
        weekday: "long",
        day: "2-digit",
        month: "long",
        year: "numeric",
        hour: "2-digit",
        minute: "2-digit"
      });
    }

    const totalPrice = price * quantity;
    // Tambahkan baris ke DataTables menggunakan template string yang benar
    var rowNode = cartTable.row.add([
      '',
      name,
      `<input type="number" value="${quantity}" min="1" 
         class="quantity-input w-16 p-1 border border-gray-300 dark:border-[#39455A] 
                rounded-md text-center bg-white text-black dark:bg-[#39455A] dark:text-gray-200" 
         onchange="updateQuantity(this)" />`,
      `<span data-total>${formatRupiah(totalPrice)}</span>`,
      kasirName,
      `<button class="text-red-500 hover:text-red-700 transition duration-300" onclick="removeRow(this)">
          <span class="material-icons text-sm">remove_shopping_cart</span>
       </button>`
    ]).draw().node();

    // Simpan data item ke array cartItems
    cartItems.push({ 
      code: code, 
      name: name, 
      quantity: quantity, 
      unitPrice: price, 
      total: totalPrice, 
      row: rowNode 
    });

    updateTotal();
    if (!fromStorage) {
      saveCart();
    }
  }

  // Update jumlah produk (dipanggil saat input quantity berubah)
  window.updateQuantity = function(input) {
    const newQuantity = parseInt(input.value);
    if (isNaN(newQuantity) || newQuantity < 1) {
      input.value = 1;
      return;
    }
    const row = input.closest("tr");
    const index = cartItems.findIndex(item => item.row === row);

    if (index !== -1) {
      cartItems[index].quantity = newQuantity;
      cartItems[index].total = cartItems[index].unitPrice * newQuantity;

      // Perbarui tampilan total pada baris
      $(row).find('[data-total]').text(formatRupiah(cartItems[index].total));

      updateTotal();
      saveCart();
    }
  };

  // Hapus baris dari keranjang
  window.removeRow = function(btn) {
    const row = btn.closest("tr");
    const index = cartItems.findIndex(item => item.row === row);
    if (index !== -1) {
      // Hapus dari DataTables
      cartTable.row(row).remove().draw();
      // Hapus dari array cartItems
      cartItems.splice(index, 1);

      // Jika keranjang kosong, reset tanggal transaksi
      if (cartItems.length === 0) {
        transactionDate = null;
        document.getElementById("transactionDate").value = "";
        localStorage.removeItem("transactionDate");
      }

      updateTotal();
      saveCart();
    }
  };

  /* -------------------------------------------------------------------------
   | 5. Fungsi Reset Keranjang
   * ----------------------------------------------------------------------- */
  window.confirmClearCart = function() {
    if (cartItems.length === 0) {
      Swal.fire({
        iconHtml: '<span class="material-icons" style="font-size:48px; color:#D32F2F;">remove_shopping_cart</span>',
        title: 'Keranjang Kosong!',
        text: 'Tidak ada data untuk direset.',
        confirmButtonColor: '#D32F2F'
      });
      return;
    }
    Swal.fire({
      title: 'Apakah kamu yakin?',
      text: "Reset keranjang belanja?",
      iconHtml: '<span class="material-icons" style="color: #d33; font-size: 50px;">restore</span>',
      showCancelButton: true,
      confirmButtonText: 'Ya, reset!',
      cancelButtonText: 'Batal',
      confirmButtonColor: '#2563eb',
      cancelButtonColor: '#d33'
    }).then((result) => {
      if (result.isConfirmed) {
        clearCart();
        Swal.fire({
          title: 'Berhasil!',
          text: 'Keranjang berhasil direset.',
          icon: 'success',
          confirmButtonColor: '#2563eb',
          timer: 1500,
          showConfirmButton: false
        });
      }
    });
  };

  function clearCart() {
    cartItems = [];
    transactionDate = null;
    document.getElementById("transactionDate").value = "";
    // Bersihkan DataTables
    cartTable.clear().draw();
    // Hapus dari localStorage
    localStorage.removeItem("cartItems");
    localStorage.removeItem("transactionDate");
    updateTotal();
  }

  /* -------------------------------------------------------------------------
   | 6. Fungsi Menghitung Total & Kembalian
   * ----------------------------------------------------------------------- */
  function updateTotal() {
    const total = cartItems.reduce((sum, item) => sum + item.total, 0);
    document.getElementById("total").value = formatRupiah(total);
    updateChange();
  }

  function updateChange() {
    const total = cartItems.reduce((sum, item) => sum + item.total, 0);
    const payment = parseInt(document.getElementById("payment").value.replace(/\D/g, "")) || 0;
    const change = payment - total;
    document.getElementById("change").value = change >= 0 ? formatRupiah(change) : formatRupiah(0);
  }
  document.getElementById("payment").addEventListener("input", updateChange);

  /* -------------------------------------------------------------------------
   | 7. Fungsi Menambahkan Barang (Daftar Barang -> Keranjang)
   * ----------------------------------------------------------------------- */
  $(document).on('click', '.add-to-cart-btn', function() {
    var row = $(this).closest('tr');
    var name = row.find('[data-name]').attr('data-name');
    var price = parseInt(row.find('[data-price]').attr('data-price'));
    var code = row.find('[data-code]').attr('data-code');
    addToCart(name, price, 1, code);
  });

  /* -------------------------------------------------------------------------
   | 8. Fitur Barcode Scanner
   * ----------------------------------------------------------------------- */
  let barcodeBuffer = "";
  let barcodeTimer = null;
  document.addEventListener("keypress", function(e) {
    if(e.target.tagName.toLowerCase() === 'input') return;
    barcodeBuffer += e.key;
    if (barcodeTimer) clearTimeout(barcodeTimer);
    barcodeTimer = setTimeout(function() {
      if (barcodeBuffer.endsWith("\r") || barcodeBuffer.endsWith("\n")) {
        processBarcode(barcodeBuffer);
      }
      barcodeBuffer = "";
    }, 50);
  });

  function processBarcode(scannedCode) {
    const code = scannedCode.trim();
    if (code) {
      var row = $("#barangTable").find("tr").filter(function() {
        return $(this).find("[data-code]").attr("data-code") === code;
      }).first();
      if (row.length) {
        row.find(".add-to-cart-btn").click();
      } else {
        Swal.fire({
          icon: 'error',
          title: 'Produk tidak ditemukan',
          text: 'Barcode tidak cocok dengan produk yang tersedia.'
        });
      }
    }
  }

  /* -------------------------------------------------------------------------
   | 9. Inisialisasi DataTables untuk Daftar Barang
   * ----------------------------------------------------------------------- */
  var table = $('#barangTable').DataTable({
    deferRender: true,
    pageLength: 5,
    lengthMenu: [5, 10, 25, 50, 100]
  });

  $('#barangTable_filter input[type="search"]').on('keypress', function(e) {
    if(e.which === 13) {
      e.preventDefault();
      let filteredRows = table.rows({ filter: 'applied' });
      if(filteredRows.count() > 0) {
        let firstRow = $(filteredRows.nodes()[0]);
        firstRow.find('.add-to-cart-btn').click();
        $(this).val('');
        table.search('').draw();
      } else {
        Swal.fire({
          icon: 'error',
          title: 'Produk tidak ditemukan',
          text: 'Tidak ada produk yang cocok dengan pencarian Anda.'
        });
      }
    }
  });

  /* -------------------------------------------------------------------------
   | 10. Load Cart Saat Halaman Dimuat
   * ----------------------------------------------------------------------- */
  loadCart();

  /* -------------------------------------------------------------------------
   | 11. Fungsi Cetak Struk (printReceipt)
   * ----------------------------------------------------------------------- */
  window.printReceipt = function() {
    const now = transactionDate || new Date();
    const formattedTransactionDate = now.toLocaleString("id-ID", {
      weekday: "long",
      day: "2-digit",
      month: "long",
      year: "numeric",
      hour: "2-digit",
      minute: "2-digit"
    });

    const total = document.getElementById("total").value;
    const payment = document.getElementById("payment").value || "0";
    const change = document.getElementById("change").value;

    let itemsContent = "";
    cartItems.forEach((item, index) => {
      itemsContent += `
          <div style="display: flex; justify-content: space-between; margin-bottom: 5px; font-size: 14px; color: #333;">
            <span>${index + 1}. ${item.name} (${item.quantity}x)</span>
            <span>${formatRupiah(item.total)}</span>
          </div>
      `;
    });

    const receiptContent = `
        <div style="font-family: 'Poppins', sans-serif; max-width: 350px; margin: auto; background: #fff; padding: 20px; border-radius: 8px; box-shadow: 0 2px 8px rgba(0,0,0,0.1);">
          <div style="text-align: center; margin-bottom: 20px; border-bottom: 1px dashed #ccc; padding-bottom: 10px;">
            <h3 style="font-size: 20px; margin: 0; color: #333;">Struk Pembayaran</h3>
            <p style="font-size: 13px; margin: 2px 0; color: #555;">Alamat: ${storeAddress}</p>
            <p style="font-size: 13px; margin: 2px 0; color: #555;">Kontak: ${storeContact}</p>
            <p style="font-size: 13px; margin: 2px 0; color: #555;">Pemilik: ${storeOwner}</p>
          </div>
          <div style="text-align: center; margin-bottom: 15px;">
            <h2 style="font-size: 22px; margin: 0 0 5px; color: #333;">${storeName}</h2>
            <p style="font-size: 12px; margin: 2px 0; color: #777;">Kasir: ${kasirName}</p>
            <p style="font-size: 12px; margin: 2px 0; color: #777;">${formattedTransactionDate}</p>
          </div>
          <div style="margin: 20px 0; border-top: 1px dashed #ccc; border-bottom: 1px dashed #ccc; padding: 10px 0;">
            ${itemsContent}
          </div>
          <div style="margin-bottom: 20px;">
            <div style="display: flex; justify-content: space-between; font-size: 16px; color: #333; margin-bottom: 5px;">
              <strong>Total:</strong>
              <span>${total}</span>
            </div>
            <div style="display: flex; justify-content: space-between; font-size: 16px; color: #333; margin-bottom: 5px;">
              <strong>Pembayaran:</strong>
              <span>${formatRupiah(parseInt(payment))}</span>
            </div>
            <div style="display: flex; justify-content: space-between; font-size: 16px; color: #333;">
              <strong>Kembalian:</strong>
              <span>${change}</span>
            </div>
          </div>
          <div style="text-align: center; border-top: 1px dashed #ccc; padding-top: 10px;">
            <p style="font-size: 14px; color: #777; margin: 0;">Terima kasih telah berbelanja!</p>
          </div>
        </div>
    `;

    const printWindow = window.open("", "", "width=400,height=600");
    printWindow.document.write(`
      <html>
        <head>
          <title>Struk Pembayaran</title>
          <link href="https://fonts.googleapis.com/css2?family=Poppins:wght@300;400;500;600&display=swap" rel="stylesheet">
          <style>
            body { margin: 0; padding: 20px; background: #f2f2f2; }
            @media print { body { -webkit-print-color-adjust: exact; } }
          </style>
        </head>
        <body>
          ${receiptContent}
          <script>
            window.onload = function() {
              setTimeout(() => {
                window.print();
                if (!/Mobi|Android/i.test(navigator.userAgent)) {
                  window.close();
                }
              }, 500);
            };
          <\/script>
        </body>
      </html>
    `);
    printWindow.document.close();
  };

  /* -------------------------------------------------------------------------
   | 12. Fungsi Simpan Transaksi (AJAX)
   * ----------------------------------------------------------------------- */
  window.saveTransaction = function() {
    if (cartItems.length === 0) {
      Swal.fire({
        iconHtml: '<span class="material-icons" style="font-size:48px; color:#D32F2F;">remove_shopping_cart</span>',
        title: 'Keranjang Kosong!',
        text: 'Tambahkan barang terlebih dahulu sebelum melakukan transaksi.',
        confirmButtonColor: '#D32F2F'
      });
      return;
    }

    const total = cartItems.reduce((sum, item) => sum + item.total, 0);
    const payment = parseInt(document.getElementById("payment").value.replace(/\D/g, "")) || 0;

    const data = {
      items: cartItems.map(item => ({
        code: item.code,
        name: item.name,
        quantity: item.quantity,
        unitPrice: item.unitPrice,
        total: item.total
      })),
      total: total,
      payment: payment,
      transaction_date: transactionDate ? transactionDate.toISOString() : null
    };

    fetch("{{ route('kasir.simpan') }}", {
      method: "POST",
      headers: {
        "Content-Type": "application/json",
        "X-CSRF-TOKEN": "{{ csrf_token() }}"
      },
      body: JSON.stringify(data)
    })
    .then(response => response.json())
    .then(result => {
      if (result.success) {
        Swal.fire({
          iconHtml: '<span class="material-icons" style="font-size:48px; color:#2E7D32;">shopping_cart_checkout</span>',
          title: 'Transaksi Berhasil',
          text: 'Transaksi berhasil disimpan!',
          timer: 2000,
          showConfirmButton: false
        });
        clearCart();
        document.getElementById("payment").value = '';
        document.getElementById("total").value = formatRupiah(0);
        document.getElementById("change").value = formatRupiah(0);
      } else {
        Swal.fire({
          iconHtml: '<span class="material-icons" style="font-size:48px; color:#F44336;">cancel</span>',
          title: 'Transaksi Gagal!',
          text: 'Gagal menyimpan transaksi: ' + result.message,
          confirmButtonColor: '#F44336'
        });
      }
    })
    .catch(error => {
      console.error("Error:", error);
      Swal.fire({
        iconHtml: '<span class="material-icons" style="font-size:48px; color:#F44336;">cancel</span>',
        title: 'Error!',
        text: 'Terjadi kesalahan saat menyimpan transaksi.',
        confirmButtonColor: '#F44336'
      });
    });
  };

  /* -------------------------------------------------------------------------
   | 13. Fungsi Download PDF Barcode via AJAX
   * ----------------------------------------------------------------------- */
  $('#downloadPdfBtn').on('click', function (e) {
  e.preventDefault();

  let url = $(this).data('url');
  if (!url) {
    console.error("URL untuk unduhan tidak ditemukan.");
    Toast.fire({
      icon: 'error',
      title: 'URL tidak valid. Silakan coba lagi.'
    });
    return;
  }

  $.ajax({
    url: url,
    method: 'GET',
    xhrFields: { responseType: 'blob' },
    success: function (data, status, xhr) {
      let filename = "barcode_list.pdf";
      const disposition = xhr.getResponseHeader('Content-Disposition');

      if (disposition && disposition.indexOf('filename=') !== -1) {
        const filenameRegex = /filename[^;=\n]*=((['"]).*?\2|[^;\n]*)/;
        const matches = filenameRegex.exec(disposition);
        if (matches !== null && matches[1]) {
          filename = matches[1].replace(/['"]/g, '');
        }
      }

      // Buat blob dari data PDF dan lakukan download
      const blob = new Blob([data], { type: 'application/pdf' });
      const link = document.createElement('a');
      link.href = window.URL.createObjectURL(blob);
      link.download = filename;
      document.body.appendChild(link);
      link.click();
      document.body.removeChild(link);

      // Tunda sedikit agar unduhan selesai, lalu tampilkan toast dengan SweetAlert2
      setTimeout(function () {
        Toast.fire({
          icon: 'success',
          title: 'PDF berhasil diunduh! 🎉'
        });
      }, 1000);
    },
    error: function (xhr, status, error) {
      console.error("Gagal mengunduh PDF:", xhr.responseText);
      Toast.fire({
        icon: 'error',
        title: 'Terjadi kesalahan saat mengunduh PDF. ❌'
      });
    }
  });
});

// Definisikan objek Toast dengan SweetAlert2
const Toast = Swal.mixin({
  toast: true,
  position: 'top-end',
  showConfirmButton: false,
  timer: 2500,
  timerProgressBar: true,
});

});
</script>
@endpush

