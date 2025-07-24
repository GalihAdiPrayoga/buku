<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class peminjaman extends Model
{
    protected $table = 'peminjamen';

    protected $fillable = ['user_id', 'buku_id', 'tanggal_pinjam', 'tanggal_kembali', 'status'];

   public function bukus()
{
    return $this->belongsToMany(Buku::class)->withPivot('jumlah');
}


}
