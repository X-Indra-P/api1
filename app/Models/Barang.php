<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Barang extends Model
{
    use HasFactory;

    protected $guarded = [];

    public function SatuanBarang()
    {
        return $this->belongsTo(SatuanBarang::class, 'satuan_id', 'id');
    }

    public function DetailPenjualan()
    {
    return $this->hasMany(DetailPenjualan::class);
    }

    public function category()
    {
        return $this->belongsTo(Category::class, 'id_kategori', 'id');
    }

    public function subcategory()
    {
        return $this->belongsTo(Subcategory::class, 'id_subkategori', 'id');
    }

    public function keranjang()
    {
        return $this->hasMany(Keranjang::class);
    }

}
