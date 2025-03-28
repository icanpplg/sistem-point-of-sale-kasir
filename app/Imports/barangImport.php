<?php

namespace App\Imports;

use App\Models\Barang;
use App\Models\Category;
use App\Models\Satuan;
use Maatwebsite\Excel\Concerns\ToModel;
use Illuminate\Support\Facades\Session;

class BarangImport implements ToModel
{
    protected $counter;

    public function __construct()
    {
        // Mendapatkan kode barang terakhir untuk menentukan penomoran selanjutnya
        $lastBarang = Barang::latest('id')->first();
        $this->counter = $lastBarang ? intval(substr($lastBarang->kode_barang, 3)) : 0;
    }

    public function model(array $row)
    {
        // Lewati baris header jika kolom pertama berisi 'Kode Barang'
        if ($row[0] === 'Kode Barang') {
            return null;
        }

        // Generate kode barang otomatis untuk setiap baris
        $this->counter++;
        $kode_barang = 'BRG' . str_pad($this->counter, 4, '0', STR_PAD_LEFT);

        // Proses Category: gunakan field "name" untuk pencarian di tabel categories
        $categoryName = $row[1] ?? null;
        $category = null;
        if ($categoryName) {
            $category = Category::firstOrCreate(['name' => $categoryName]);
            if ($category->wasRecentlyCreated) {
                Session::push('kategori_baru', $categoryName);
            }
        }

        // Proses Satuan: gunakan field "name" karena kolom di tabel satuans adalah "name"
        $satuanName = $row[6] ?? null;
        $satuan = null;
        if ($satuanName) {
            $satuan = Satuan::firstOrCreate(['name' => $satuanName]);
            if ($satuan->wasRecentlyCreated) {
                Session::push('satuan_baru', $satuanName);
            }
        }

        return new Barang([
            'kode_barang' => $kode_barang,
            // Simpan ID dari Category dan Satuan ke field 'kategori' dan 'satuan'
            'kategori'    => $category ? $category->id : null,
            'merek'       => $row[2] ?? null,
            'nama_produk' => $row[3] ?? null,
            'harga_beli'  => $row[4] ?? 0,
            'harga_jual'  => $row[5] ?? 0,
            'satuan'      => $satuan ? $satuan->id : null,
            'stok'        => $row[7] ?? 0,
        ]);
    }
}
