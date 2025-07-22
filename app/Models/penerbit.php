<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class penerbit extends Model
{
    protected $table = 'penerbits';

    protected $guarded = [];

    public function bukus()
    {
        return $this->hasMany(buku::class);
    }
}
