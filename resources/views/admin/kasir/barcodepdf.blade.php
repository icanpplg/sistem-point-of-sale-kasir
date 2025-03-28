@extends('layouts.barcodepdf')

@section('title', 'Barcode Barang')

@section('content')
<style>
  /* Mengatur tabel agar rapi saat di-print */
  table {
    width: 100%;
    border-collapse: collapse;
    /* Membantu DOMPDF mencegah pemotongan baris di tengah */
    page-break-inside: auto;
  }
  tr {
    page-break-inside: avoid; 
  }
  td {
    text-align: center;
    border: 1px solid #ccc;
    padding: 10px;
    vertical-align: top;
    /* Hindari pemotongan isi sel */
    page-break-inside: avoid;
  }
  /* Styling teks di bawah barcode */
  .barcode-label {
    margin-top: 5px;
    font-size: 14px;
    font-weight: bold;
  }
</style>

<h2 style="text-align: center;">Daftar Barcode</h2>

<table>
  {{-- chunk(3) artinya setiap baris tabel akan memuat 3 barcode --}}
  @foreach ($barangs->chunk(3) as $rowBarangs)
    <tr>
      @foreach ($rowBarangs as $barang)
        <td>
          {!! DNS1D::getBarcodeHTML($barang->kode_barang, 'C128', 1.5, 40) !!}
          <p class="barcode-label">{{ $barang->kode_barang }}</p>
        </td>
      @endforeach
      {{-- Jika jumlah barcode tidak genap 3, sel yang tersisa tetap kosong agar layout stabil --}}
      @for($i = $rowBarangs->count(); $i < 3; $i++)
        <td></td>
      @endfor
    </tr>
  @endforeach
</table>
@endsection
