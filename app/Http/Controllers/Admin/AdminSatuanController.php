<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Satuan;
use Illuminate\Http\Request;

class AdminSatuanController extends Controller
{
    /**
     * Menampilkan daftar satuan.
     */
    public function index()
    {
        // Ambil semua satuan dengan urutan terbaru berdasarkan tanggal dibuat
        $satuans = Satuan::orderBy('created_at', 'desc')->get();
        return view('admin.satuan.index', compact('satuans'));
    }

    /**
     * Menyimpan satuan baru.
     */
    public function store(Request $request)
    {
        // Validasi input
        $validatedData = $request->validate([
            'name' => 'required|string|max:255',
        ]);

        // Buat data satuan baru
        $satuan = Satuan::create($validatedData);

        return response()->json([
            'success' => 'Satuan berhasil ditambahkan!',
            'satuan'  => $satuan,
        ]);
    }

    /**
     * Memperbarui data satuan.
     */
    public function update(Request $request, $id)
    {
        // Validasi input
        $validatedData = $request->validate([
            'name' => 'required|string|max:255',
        ]);

        // Cari satuan berdasarkan ID dan perbarui data
        $satuan = Satuan::findOrFail($id);
        $satuan->update($validatedData);

        return response()->json([
            'success' => 'Satuan berhasil diperbarui!',
            'satuan'  => $satuan,
        ]);
    }

    /**
     * Menghapus data satuan.
     */
    public function destroy($id)
    {
        // Cari satuan berdasarkan ID dan hapus data
        $satuan = Satuan::findOrFail($id);
        $satuan->delete();

        return response()->json([
            'success' => 'Satuan berhasil dihapus!'
        ]);
    }
}
