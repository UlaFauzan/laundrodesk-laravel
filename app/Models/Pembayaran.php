<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Pembayaran extends Model
{
    use HasFactory;

    protected $table = 'pembayaran';
    protected $fillable = [
        'transaksi_id',
        'metode_pembayaran',
        'jumlah_bayar',
        'total_bayar',
        'status_pembayaran',
        'status_bayar',
        'qr_token',
        'notified'
    ];

public function transaksi()
{
    return $this->belongsTo(Transaksi::class, 'transaksi_id');
}

public function getIdAttribute()
{
    return $this->attributes[$this->getKeyName()] ?? null;
}

public function getJumlahBayarAttribute()
{
    return $this->attributes['jumlah_bayar'] ?? $this->attributes['total_bayar'] ?? null;
}

public function setJumlahBayarAttribute($value)
{
    $this->attributes['jumlah_bayar'] = $value;
}

public function getStatusPembayaranAttribute()
{
    return $this->attributes['status_pembayaran'] ?? $this->attributes['status_bayar'] ?? null;
}

public function setStatusPembayaranAttribute($value)
{
    $this->attributes['status_pembayaran'] = $value;
}
}
