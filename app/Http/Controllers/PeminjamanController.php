<?php

namespace App\Http\Controllers;

use App\Models\peminjaman;
use Illuminate\Http\Request;

class PeminjamanController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        $peminjaman = peminjaman::with('bukus')->where('user_id', auth()->id())->get();

        return response()->json([
            'message' => 'List of Peminjaman',
            'data' => $peminjaman
        ], 200);
    }

    public function store(Request $request)
    {
           $request->validate([
        'buku_ids' => 'required|array',
        'buku_ids.*.id' => 'required|exists:bukus,id',
        'buku_ids.*.jumlah' => 'required|integer|min:1'
    ]);

    DB::beginTransaction();

    try {
        $peminjaman = Peminjaman::create([
            'user_id' => auth()->id(),
            'tanggal_pinjam' => now(),
            'tanggal_kembali' => now()->addDays(7) // contoh
        ]);

        foreach ($request->buku_ids as $bukuItem) {
            $buku = Buku::findOrFail($bukuItem['id']);

            if ($buku->stok < $bukuItem['jumlah']) {
                throw new \Exception("Stok buku '{$buku->nama}' tidak mencukupi.");
            }

            // Kurangi stok
            $buku->stok -= $bukuItem['jumlah'];
            $buku->save();

            // Tambahkan ke pivot
            $peminjaman->bukus()->attach($buku->id, ['jumlah' => $bukuItem['jumlah']]);
        }

        DB::commit();

        return response()->json([
            'message' => 'Peminjaman berhasil dibuat',
            'data' => $peminjaman->load('bukus')
        ], 201);
    } catch (\Exception $e) {
        DB::rollBack();
        return response()->json([
            'message' => 'Gagal melakukan peminjaman',
            'error' => $e->getMessage()
        ], 400);
    }
    }

    /**
     * Display the specified resource.
     */
    public function show(peminjaman $peminjaman)
    {
        
    }

    public function update(Request $request, peminjaman $peminjaman)
    {
        $request->validate([
            'tanggal_kembali' => 'required|date',
            'status' => 'required|in:dipinjam,dikembalikan'
        ]);

        $peminjaman->update([
            'tanggal_kembali' => $request->tanggal_kembali,
            'status' => $request->status
        ]);

        return response()->json([
            'message' => 'Peminjaman berhasil diperbarui',
            'data' => $peminjaman
        ], 200);
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(peminjaman $peminjaman)
    {
        $peminjaman->delete();

        return response()->json([
            'message' => 'Peminjaman berhasil dihapus'
        ], 200);
    }
}
