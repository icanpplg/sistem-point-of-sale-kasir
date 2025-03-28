<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Barang;
use App\Models\Category;
use App\Models\Satuan;
use Illuminate\Http\Request;
use Maatwebsite\Excel\Facades\Excel;
use App\Imports\BarangImport;
use Illuminate\Support\Facades\DB;

class AdminstokbarangController extends Controller
{
    public function index()
    {
        // Eager load relasi 'category' dan 'satuanRelation'
        $barangs    = Barang::with(['category', 'satuanRelation'])->get();
        $categories = Category::all();
        $satuans    = Satuan::all();

        return view('admin.stokbarang.index', compact('barangs', 'categories', 'satuans'));
    }

    public function store(Request $request)
    {
        $validated = $request->validate([
            'kategori'    => 'required|exists:categories,id',
            'merek'       => 'required|max:100',
            'nama_produk' => 'required|max:150',
            'harga_beli'  => 'required|numeric',
            'harga_jual'  => 'required|numeric',
            'satuan'      => 'required|exists:satuans,id',
            'stok'        => 'required|integer',
        ]);

        // Generate kode_barang otomatis
        $lastBarang = Barang::latest('id')->first();
        $newCode = 'BRG' . str_pad(
            ($lastBarang ? intval(substr($lastBarang->kode_barang, 3)) + 1 : 1),
            4,
            '0',
            STR_PAD_LEFT
        );
        $validated['kode_barang'] = $newCode;

        $barang = Barang::create($validated);

        if ($request->ajax()) {
            // Eager load relasi
            $barang->load(['category', 'satuanRelation']);

            return response()->json([
                'success' => 'Barang berhasil ditambahkan.',
                'barang'  => [
                    'id'          => $barang->id,
                    'kode_barang' => $barang->kode_barang,
                    'kategori_id' => $barang->category ? $barang->category->id : null,
                    'kategori'    => $barang->category ? $barang->category->name : '-',
                    'merek'       => $barang->merek,
                    'nama_produk' => $barang->nama_produk,
                    'harga_beli'  => $barang->harga_beli,
                    'harga_jual'  => $barang->harga_jual,
                    'satuan_id'   => $barang->satuanRelation ? $barang->satuanRelation->id : null,
                    'satuan'      => $barang->satuanRelation ? $barang->satuanRelation->name : '-',
                    'stok'        => $barang->stok,
                    'created_at'  => $barang->created_at ? $barang->created_at->format('d/m/Y H:i') : null,
                ]
            ]);
        }

        return redirect()->route('admin.stokbarang.index')->with('success', 'Barang berhasil ditambahkan.');
    }

    public function update(Request $request, $id)
    {
        $validated = $request->validate([
            'kategori'    => 'nullable|exists:categories,id',
            'merek'       => 'nullable|max:100',
            'nama_produk' => 'nullable|max:150',
            'harga_beli'  => 'nullable|numeric',
            'harga_jual'  => 'nullable|numeric',
            'satuan'      => 'nullable|exists:satuans,id',
            'stok'        => 'nullable|integer',
        ]);

        $barang = Barang::findOrFail($id);

        // Hanya update field yang diisi
        $dataToUpdate = array_filter($validated, function ($value) {
            return !is_null($value) && $value !== '';
        });

        $barang->update($dataToUpdate);

        // Eager load relasi setelah update
        $barang->load(['category', 'satuanRelation']);

        return response()->json([
            'success' => 'Barang berhasil diperbarui.',
            'barang'  => [
                'id'          => $barang->id,
                'kode_barang' => $barang->kode_barang,
                'kategori_id' => $barang->category ? $barang->category->id : null,
                'kategori'    => $barang->category ? $barang->category->name : '-',
                'merek'       => $barang->merek,
                'nama_produk' => $barang->nama_produk,
                'harga_beli'  => $barang->harga_beli,
                'harga_jual'  => $barang->harga_jual,
                'satuan_id'   => $barang->satuanRelation ? $barang->satuanRelation->id : null,
                'satuan'      => $barang->satuanRelation ? $barang->satuanRelation->name : '-',
                'stok'        => $barang->stok,
                'created_at'  => $barang->created_at ? $barang->created_at->format('d/m/Y H:i') : null,
            ],
        ]);
    }

    public function destroy(Request $request, $id)
    {
        $barang = Barang::findOrFail($id);
        $barang->delete();

        if ($request->ajax()) {
            return response()->json(['success' => 'Barang berhasil dihapus.']);
        }

        return redirect()->route('admin.stokbarang.index')->with('success', 'Barang berhasil dihapus.');
    }

    public function import(Request $request)
    {
        $request->validate([
            'file' => 'required|mimes:xlsx,xls,csv|max:12048',
        ]);

        try {
            if (!$request->hasFile('file')) {
                if ($request->ajax()) {
                    return response()->json(['error' => 'File tidak ditemukan!'], 400);
                }
                return redirect()->route('admin.stokbarang.index')->with('error', 'File tidak ditemukan!');
            }

            // Proses import
            Excel::import(new BarangImport, $request->file('file'));

            // Ambil data barang dengan relasi
            $barangs = Barang::with(['category', 'satuanRelation'])->get();

            // Transform barang agar menampilkan ID & nama kategori/satuan
            $barangsArray = $barangs->map(function ($b) {
                return [
                    'id'          => $b->id,
                    'kode_barang' => $b->kode_barang,
                    'kategori_id' => $b->category ? $b->category->id : null,
                    'kategori'    => $b->category ? $b->category->name : '-',
                    'merek'       => $b->merek,
                    'nama_produk' => $b->nama_produk,
                    'harga_beli'  => $b->harga_beli,
                    'harga_jual'  => $b->harga_jual,
                    'satuan_id'   => $b->satuanRelation ? $b->satuanRelation->id : null,
                    'satuan'      => $b->satuanRelation ? $b->satuanRelation->name : '-',
                    'stok'        => $b->stok,
                    'created_at'  => $b->created_at ? $b->created_at->format('d/m/Y H:i') : '-'
                ];
            });

            // Ambil daftar kategori & satuan terbaru
            $categories = Category::select('id', 'name')->get();
            $satuans    = Satuan::select('id', 'name')->get();

            if ($request->ajax()) {
                return response()->json([
                    'message'    => 'Data berhasil diimpor!',
                    'barangs'    => $barangsArray,
                    'categories' => $categories,
                    'satuans'    => $satuans
                ]);
            }

            return redirect()->route('admin.stokbarang.index')->with('success', 'Data berhasil diimpor!');
        } catch (\Exception $e) {
            if ($request->ajax()) {
                return response()->json(['error' => 'Terjadi kesalahan: ' . $e->getMessage()], 400);
            }
            return redirect()->route('admin.stokbarang.index')->with('error', 'Terjadi kesalahan: ' . $e->getMessage());
        }
    }

    // Contoh method tambahan jika Anda butuh transaksi, dsb.
    public function processTransaction(Request $request)
    {
        $validated = $request->validate([
            'transactions'             => 'required|array',
            'transactions.*.id'       => 'required|exists:barang,id',
            'transactions.*.quantity' => 'required|integer|min:1',
        ]);

        try {
            DB::transaction(function () use ($validated) {
                foreach ($validated['transactions'] as $trans) {
                    $barang = Barang::find($trans['id']);
                    if ($barang->stok < $trans['quantity']) {
                        throw new \Exception("Stok barang {$barang->nama_produk} tidak mencukupi. Tersisa: {$barang->stok}.");
                    }
                    $barang->stok -= $trans['quantity'];
                    $barang->save();
                }
            });
        } catch (\Exception $e) {
            return response()->json(['error' => $e->getMessage()], 400);
        }

        return response()->json(['success' => 'Transaksi berhasil dan stok barang telah diperbarui.']);
    }

    public function checkLowStock()
    {
        $lowStockItems = Barang::where('stok', '<=', 5)->get();
        return response()->json($lowStockItems);
    }

    // Method untuk mereset semua data barang tanpa reload halaman
    public function resetAll(Request $request)
    {
        try {
            // Cek jumlah data barang
            $count = Barang::count();
            
            // Jika data kosong, kembalikan pesan error / info
            if ($count === 0) {
                return response()->json([
                    'error' => 'Tidak ada data yang bisa direset.'
                ], 400);
            }
    
            // Jika ada data, baru lakukan truncate
            Barang::truncate();
    
            return response()->json([
                'success' => 'Semua data barang telah direset.'
            ]);
        } catch (\Exception $e) {
            return response()->json([
                'error' => 'Terjadi kesalahan: ' . $e->getMessage()
            ], 400);
        }
    }
    
}
