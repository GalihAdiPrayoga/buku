<?php

namespace App\Http\Controllers;

use App\Models\buku;
use App\Models\penerbit;
use Illuminate\Http\Request;
use App\Http\Requests\StoreBuku;
use App\Http\Requests\UpdateBuku;

class BukuController extends Controller
{
  
    protected $buku;

    public function __construct(buku $buku)
    {
        $this->buku = $buku;
    }
    public function index()
    {
     $buku =  $this->buku->with('kategori','penerbit')->get();


        return response()->json([
            'message' => 'List of Books',
            'data' => $buku
        ], 200);
    }

    public function store(StoreBuku $request)
    {
       
        
        $path = null;
        if ($request->hasFile('cover')) {
            $path = $request->file('cover')->store('covers', 'public');
        }

        $buku =  $this->buku->create([
            'nama' => $request->nama,
            'penerbit_id' => $request->penerbit_id,
            'stok' => $request->stok,
            'pengarang' => $request->pengarang,  
            'kategori_id' => $request->kategori_id,   
            'cover' => $path,
                  
        ]);

        return response()->json([
            'message' => 'buku berhasil ditambahkan',
            'data' => $buku
        ]);
    }

    public function show(buku $buku)
    {
     $buku =  $this->buku->with('kategori', 'penerbit')->find($buku->id);

    if (!$buku) {
        return response()->json([
            'message' => 'Buku tidak ditemukan',
        ], 404);
    }

    return
    response()->json([
        'message' => 'Detail Buku',
        'data' => $buku
    ], 200);
     
    }
    
    public function update(UpdateBuku $request, buku $buku)
    {
     

      if ($request->hasFile('cover') && $buku->cover) {
        \Storage::disk('public')->delete($buku->cover);
      }

      $buku->update([
        'nama' => $request->nama,
        'penerbit_id' => $request->penerbit_id,
        'stok' => $request->stok,
        'pengarang' => $request->pengarang,       
        'kategori_id' => $request->kategori_id,    
        'cover' => $request->hasFile('cover') ? $request->file('cover')->store('covers', 'public') : $buku->cover,
      ]);

      

      return response()-> json([
        'message' => 'Book updated successfully',
        'data' => $buku
      ]);
    }

    public function destroy(buku $buku)
    {
        $buku->delete();

        if ($buku->cover) {
        \Storage::disk('public')->delete($buku->cover);
    }

        return response()->json([
            'message' => 'Book deleted successfully',
        ], 200);
    }
}
