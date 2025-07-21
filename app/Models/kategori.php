<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class kategori extends Model
{
    protected $table = 'kategoris';
    protected $guarded = ['id'];

public function bukus()
    {
        return $this->hasMany(buku::class, 'kategori_id');
    }
}
