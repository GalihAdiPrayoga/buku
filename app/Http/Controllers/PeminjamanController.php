<?php

namespace App\Http\Controllers;

use App\Models\peminjaman;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use App\Models\Buku;
use App\Http\Requests\StorePeminjaman;

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

    public function store(StorePeminjaman $request)
    {
         

    DB::beginTransaction();

    try {
        $peminjaman = Peminjaman::create([
            'user_id' => auth()->id(),
            'tanggal_pinjam' => now(),
            'tanggal_kembali' => now()->addDays(7) 
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
        $peminjaman = peminjaman::with('bukus')->find($peminjaman->id);
        if (!$peminjaman) {
            return response()->json([
                'message' => 'Peminjaman tidak ditemukan',
                'data' => null
            ], 404);
        }
        return response()->json([
            'message' => 'Detail Peminjaman',
            'data' => $peminjaman
        ], 200);
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

    public function destroy(peminjaman $peminjaman)
    {
        $peminjaman->delete();

        return response()->json([
            'message' => 'Peminjaman berhasil dihapus'
        ], 200);
    }
}
