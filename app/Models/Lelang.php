<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Lelang extends Model
{
    use HasFactory;

    protected $fillable = [
        'nama_barang',
        'harga_awal',
        'waktu_berakhir',
        'status',
    ];
}
