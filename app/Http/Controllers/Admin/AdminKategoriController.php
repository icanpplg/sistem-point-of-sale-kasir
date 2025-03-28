<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Category;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Log;

class AdminKategoriController extends Controller
{
    /**
     * Menampilkan daftar kategori.
     */
    public function index()
    {
        $categories = Category::all();
        return view('admin.kategori.index', compact('categories'));
    }

    /**
     * Menyimpan kategori baru.
     */
    public function store(Request $request)
    {
        try {
            $validated = $request->validate([
                'name' => 'required|string|max:255',
            ]);

            $category = Category::create($validated);

            if ($request->expectsJson()) {
                return response()->json([
                    'success'  => 'Kategori berhasil ditambahkan!',
                    'category' => $category,
                ], 200);
            }

            return redirect()->back()->with('success', 'Kategori berhasil ditambahkan!');
        } catch (\Exception $e) {
            Log::error("Error storing category: " . $e->getMessage());

            if ($request->expectsJson()) {
                return response()->json([
                    'error' => 'Gagal menambahkan kategori! ' . $e->getMessage(),
                ], 500);
            }

            return redirect()->back()->with('error', 'Gagal menambahkan kategori!');
        }
    }

    /**
     * Memperbarui kategori.
     */
    public function update(Request $request, $id)
    {
        try {
            $validated = $request->validate([
                'name' => 'required|string|max:255',
            ]);

            $category = Category::findOrFail($id);
            $category->update($validated);

            if ($request->expectsJson()) {
                return response()->json([
                    'success'  => 'Kategori berhasil diperbarui!',
                    'category' => $category,
                ], 200);
            }

            return redirect()->route('admin.kategori.index')->with('success', 'Kategori berhasil diperbarui!');
        } catch (\Exception $e) {
            Log::error("Error updating category (ID: $id): " . $e->getMessage());

            if ($request->expectsJson()) {
                return response()->json([
                    'error' => 'Gagal memperbarui kategori! ' . $e->getMessage(),
                ], 500);
            }

            return redirect()->back()->with('error', 'Gagal memperbarui kategori!');
        }
    }

    /**
     * Menghapus kategori.
     */
    public function destroy(Request $request, $id)
    {
        try {
            $category = Category::findOrFail($id);
            
            // Jika kategori memiliki relasi dengan model lain (misal produk), Anda bisa
            // memeriksa dan mengelola relasi tersebut terlebih dahulu.
            $category->delete();

            if ($request->expectsJson()) {
                return response()->json([
                    'success' => 'Kategori berhasil dihapus!',
                ], 200);
            }

            return redirect()->back()->with('success', 'Kategori berhasil dihapus!');
        } catch (\Exception $e) {
            Log::error("Error deleting category (ID: $id): " . $e->getMessage());
            
            $errorMessage = $e->getMessage();
            if (strpos($errorMessage, 'foreign key constraint fails') !== false) {
                $errorMessage = 'Kategori tidak dapat dihapus karena memiliki data terkait.';
            }

            if ($request->expectsJson()) {
                return response()->json([
                    'error' => 'Gagal menghapus kategori! ' . $errorMessage,
                ], 500);
            }

            return redirect()->back()->with('error', 'Gagal menghapus kategori! ' . $errorMessage);
        }
    }
}
