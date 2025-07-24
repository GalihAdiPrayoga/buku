<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class buku extends Model
{
    protected $table = 'bukus';

    protected $guarded = [];

    public function kategori()
    {
        return $this->belongsTo(kategori::class, 'kategori_id');
    }
    public function penerbit()
    {
        return $this->belongsTo(penerbit::class);
    }

    public function peminjamen()
    {
        return $this->belongsToMany(peminjaman::class, 'buku_peminjaman')->withPivot('jumlah');
    }
}




