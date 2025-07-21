<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class buku extends Model
{
    protected $table = 'bukus';

    protected $fillable = ['nama','pengarang','penerbit','stok','cover'];

    public function kategori()
{
    return $this->belongsTo(kategori::class, 'kategori_id');
}
}




