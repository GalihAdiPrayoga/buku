<?php

namespace App\Http\Controllers;

use App\Models\kategori;
use Illuminate\Http\Request;

class KategoriController extends Controller
{
    public function __construct()
   {
        $this->kategori = new kategori;
    }
    public function index()
    {
        $kategoris = $this->kategori->all();
        return response()->json([
            'message' => 'List of Categories',
            'data' => $kategoris
        ], 200);
    }

    public function store(Request $request)
    {
        $request->validate([
            'nama' => 'required|string|max:255|unique:kategoris,nama',
        ]);

        $kategori = $this->kategori->create([
            'nama' => $request->nama,
        ]);

        return response()->json([
            'message' => 'Kategori berhasil ditambahkan',
            'data' => $kategori
        ], 201);
    }

    /**
     * Display the specified resource.
     */
    public function show(kategori $kategori)
    {
        $kategori = $this->kategori->find($kategori->id);

        if (!$kategori) {
            return response()->json([
                'message' => 'Kategori tidak ditemukan',
            ], 404);
        }

        return response()->json([
            'message' => 'Detail Kategori',
            'data' => $kategori
        ], 200);
    }


    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, kategori $kategori)
    {
        $request->validate([
            'nama' => 'required|string|max:255|unique:kategoris,nama,' . $kategori->id,
        ]);

        $kategori->update([
            'nama' => $request->nama,
        ]);

        return response()->json([
            'message' => 'Kategori berhasil diperbarui',
            'data' => $kategori,
        ], 200);
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(kategori $kategori)
    {
        $kategori->delete();

        return response()->json([
            'message' => 'Kategori berhasil dihapus',
        ], 200);
    }
}
