<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class peminjaman extends Model
{
    protected $table = 'peminjamen';

    protected $fillable = ['user_id', 'buku_id', 'tanggal_pinjam', 'tanggal_kembali', 'status'];

    public function buku()
    {
         return $this->belongsToMany(Buku::class, 'buku_peminjaman')->withPivot('jumlah');

    }

}
