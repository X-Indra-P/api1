<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class SatuanBarang extends Model
{
    use HasFactory;
    protected $guarded = [];

    public function Barang()
    {
        return $this->hasMany(Barang::class);
    }
}
