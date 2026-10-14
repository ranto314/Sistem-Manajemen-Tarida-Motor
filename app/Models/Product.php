<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Product extends Model
{
    use HasFactory;

    protected $fillable = [
        'category_id',
        'kode_barang',
        'nama_barang',
        'tipe_kendaraan',
        'tahun',
        'satuan',
        'harga_beli',
        'harga_jual',
        'stok',
        'keterangan',
    ];

    public function category()
    {
        return $this->belongsTo(Category::class);
    }

    public function transactionDetails()
    {
        return $this->hasMany(TransactionDetail::class);
    }
}