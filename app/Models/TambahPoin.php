<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class TambahPoin extends Model
{
    use HasFactory;

    protected $table = 'tambah_poin';

    protected $fillable = [
        'pelanggan_id',
        'transaksi_id',
        'jumlah_poin',
        'alasan',
    ];

    public function pelanggan()
    {
        return $this->belongsTo(Pelanggan::class, 'pelanggan_id');
    }

    public function transaksi()
    {
        return $this->belongsTo(Transaksi::class, 'transaksi_id');
    }
}
