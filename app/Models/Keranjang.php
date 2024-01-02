<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Keranjang extends Model
{
    use HasFactory;
    protected $guarded = [];

    public function member()
    {
        return $this->belongsTo(Member::class, 'id_member', 'id');
    }

    public function barang()
    {
        return $this->belongsTo(Barang::class, 'id_barang', 'id');
    }
}
