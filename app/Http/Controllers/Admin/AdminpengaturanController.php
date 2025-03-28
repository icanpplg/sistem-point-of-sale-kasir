<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\Pengaturan;

class AdminpengaturanController extends Controller
{
    public function index()
    {
        // Ambil record pertama dari tabel pengaturans.
        // Jika belum ada, kembalikan null sehingga Blade bisa menampilkannya sebagai form kosong.
        $pengaturan = Pengaturan::first();
        return view('admin.pengaturan.index', compact('pengaturan'));
    }

    public function update(Request $request)
    {
        // Validasi input
        $data = $request->validate([
            'store_name'    => 'required|string|max:255',
            'store_address' => 'required|string',
            'store_contact' => 'required|string|max:50',
            'store_owner'   => 'required|string|max:255',
        ]);

        // Ambil record pertama; jika belum ada, buat instance baru
        $pengaturan = Pengaturan::first();
        if (!$pengaturan) {
            $pengaturan = new Pengaturan();
        }
        
        // Perbarui field dan simpan
        $pengaturan->store_name    = $data['store_name'];
        $pengaturan->store_address = $data['store_address'];
        $pengaturan->store_contact = $data['store_contact'];
        $pengaturan->store_owner   = $data['store_owner'];
        $pengaturan->save();

        // Kembalikan JSON dengan data terbaru
        return response()->json([
            'success'    => true,
            'message'    => 'Pengaturan toko berhasil diperbarui.',
            'pengaturan' => $pengaturan
        ]);
    }
}
