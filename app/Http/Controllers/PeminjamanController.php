<?php

namespace App\Http\Controllers;

use App\Models\peminjaman;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use App\Models\Buku;
use App\Http\Requests\StorePeminjaman;
use App\Http\Requests\UpdatePeminjaman;


class PeminjamanController extends Controller
{
    public function __construct()
    {
        $this->peminjaman = new peminjaman;
    }   
    public function index()
    {
        $peminjaman = $this->peminjaman->with('bukus')->where('user_id', auth()->id())->get();

        return response()->json([
            'message' => 'List of Peminjaman',
            'data' => $peminjaman
        ], 200);
    }

    public function store(StorePeminjaman $request)
    {
        
    DB::beginTransaction();

    try {
        $peminjaman = $this->peminjaman->create([
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
    public function show(peminjaman $peminjaman)
    {
        $peminjaman = $this->peminjaman->with('bukus')->find($peminjaman->id);
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

    public function update(UpdatePeminjaman $request, peminjaman $peminjaman)
    {
        // $request->validate();

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
