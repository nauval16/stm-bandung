<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Jalan;
use Illuminate\Http\Request;

class JalanController extends Controller
{
    public function index()
    {
        $jalans = Jalan::latest()->get();
        return view('admin.jalan.index', compact('jalans'));
    }

    // Menampilkan halaman form tambah data
    public function create()
    {
        return view('admin.jalan.create');
    }

    // Memproses data yang dikirim dari form ke database
    public function store(Request $request)
    {
        $request->validate([
            'nama_jalan' => 'required|string|max:255',
            'kecamatan' => 'nullable|string|max:255',
        ]);

        Jalan::create([
            'nama_jalan' => $request->nama_jalan,
            'kecamatan' => $request->kecamatan,
        ]);

        return redirect()->route('admin.jalan.index')->with('success', 'Data jalan berhasil ditambahkan!');
    }

    // Menampilkan form edit dengan data lama
    public function edit(Jalan $jalan)
    {
        return view('admin.jalan.edit', compact('jalan'));
    }

    // Memproses perubahan data ke database
    public function update(Request $request, Jalan $jalan)
    {
        $request->validate([
            'nama_jalan' => 'required|string|max:255',
            'kecamatan' => 'nullable|string|max:255',
        ]);

        $jalan->update([
            'nama_jalan' => $request->nama_jalan,
            'kecamatan' => $request->kecamatan,
        ]);

        return redirect()->route('admin.jalan.index')->with('success', 'Data jalan berhasil diperbarui!');
    }

    // Menghapus data dari database
    public function destroy(Jalan $jalan)
    {
        $jalan->delete();
        return redirect()->route('admin.jalan.index')->with('success', 'Data jalan berhasil dihapus!');
    }
}