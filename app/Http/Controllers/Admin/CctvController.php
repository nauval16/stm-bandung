<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Cctv;
use App\Models\Jalan;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;

class CctvController extends Controller
{
    public function index()
    {
        $cctvs = Cctv::with('jalan')->latest()->get();
        return view('admin.cctv.index', compact('cctvs'));
    }

    // Menampilkan form tambah
    public function create()
    {
        // Ambil semua data jalan untuk ditampilkan di dropdown (select)
        $jalans = Jalan::orderBy('nama_jalan', 'asc')->get();
        return view('admin.cctv.create', compact('jalans'));
    }

    // Memproses form tambah
    public function store(Request $request)
    {
        $request->validate([
            'jalan_id'   => 'required|exists:jalans,id',
            'link_akses' => 'required|url',
            'latitude'   => 'nullable|string', // <-- Tambah ini
            'longitude'  => 'nullable|string', // <-- Tambah ini
            'gambar'     => 'required|image|mimes:jpg,jpeg,png|max:2048',
        ]);

        $gambarPath = $request->file('gambar')->store('cctv', 'public');

        Cctv::create([
            'jalan_id'   => $request->jalan_id,
            'link_akses' => $request->link_akses,
            'latitude'   => $request->latitude,   // <-- Tambah ini
            'longitude'  => $request->longitude,  // <-- Tambah ini
            'gambar'     => $gambarPath,
        ]);

        return redirect()->route('admin.cctv.index')->with('success', 'Perangkat CCTV berhasil ditambahkan!');
    }
    // Menampilkan form edit
    public function edit(Cctv $cctv)
    {
        $jalans = Jalan::orderBy('nama_jalan', 'asc')->get();
        return view('admin.cctv.edit', compact('cctv', 'jalans'));
    }

    // Memproses update data dan gambar
    public function update(Request $request, Cctv $cctv)
    {
        $request->validate([
            'jalan_id'   => 'required|exists:jalans,id',
            'link_akses' => 'required|url',
            'latitude'   => 'nullable|string', // <-- Tambah ini
            'longitude'  => 'nullable|string', // <-- Tambah ini
            'gambar'     => 'nullable|image|mimes:jpg,jpeg,png|max:2048',
        ]);

        $data = [
            'jalan_id'   => $request->jalan_id,
            'link_akses' => $request->link_akses,
            'latitude'   => $request->latitude,   // <-- Tambah ini
            'longitude'  => $request->longitude,  // <-- Tambah ini
        ];

        // Jika admin mengupload gambar baru
        if ($request->hasFile('gambar')) {
            // Hapus gambar lama dari server jika ada
            if ($cctv->gambar && Storage::disk('public')->exists($cctv->gambar)) {
                Storage::disk('public')->delete($cctv->gambar);
            }
            // Simpan gambar baru
            $data['gambar'] = $request->file('gambar')->store('cctv', 'public');
        }

        $cctv->update($data);

        return redirect()->route('admin.cctv.index')->with('success', 'Perangkat CCTV berhasil diperbarui!');
    }

    // Menghapus data dan gambar
    public function destroy(Cctv $cctv)
    {
        // Hapus gambar fisik dari server sebelum data dihapus dari database
        if ($cctv->gambar && Storage::disk('public')->exists($cctv->gambar)) {
            Storage::disk('public')->delete($cctv->gambar);
        }
        
        $cctv->delete();
        
        return redirect()->route('admin.cctv.index')->with('success', 'Perangkat CCTV berhasil dihapus!');
    }
}