<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Foundation\Auth\User as Authenticatable;

class Member extends Authenticatable
{
    use HasFactory;

    protected $guarded = [];

public function pesanan()
{
    return $this->hasMany(Pesanan::class);
}

public function penjualan()
{
    return $this->hasMany(Penjualan::class);
}

public function keranjang()
    {
        return $this->hasMany(Keranjang::class);
    }

}
