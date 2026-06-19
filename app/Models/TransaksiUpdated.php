<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use App\Models\Pelanggan;
use App\Models\Layanan;
use App\Models\StatusLaundry;

class Transaksi extends Model
{
    use HasFactory;

    protected $table = 'transaksi';

    protected $fillable = [
        'pelanggan_id',
        'layanan_id',
        'berat_kg',
        'total_harga',
        'status',
        'tanggal_masuk',
        'tanggal_selesai',
        'status_laundry_id'
    ];

    public function pelanggan()
    {
        return $this->belongsTo(Pelanggan::class);
    }

    public function layanan()
    {
        return $this->belongsTo(Layanan::class);
    }

    public function statusLaundry()
    {
        return $this->belongsTo(StatusLaundry::class);
    }

    public function pembayaran()
    {
        return $this->hasOne(Pembayaran::class);
    }
    
    public function detailTransaksi()
    {
        return $this->hasMany(DetailTransaksi::class);
    }
}
