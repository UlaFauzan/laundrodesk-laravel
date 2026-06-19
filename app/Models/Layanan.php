<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Layanan extends Model
{
    use HasFactory;

    protected $table = 'layanan';
    protected $fillable = [
        'nama_layanan',
        'harga_per_kg',
        'harga_perkg',
        'deskripsi',
        'estimasi_hari',
    ];

    public function transaksi()
{
    return $this->hasManyThrough(
        Transaksi::class,
        DetailTransaksi::class,
        'layanan_id',
        'transaksi_id',
        'layanan_id',
        'transaksi_id'
    );
}

public function getIdAttribute()
{
    return $this->attributes[$this->getKeyName()] ?? null;
}

public function getHargaPerKgAttribute()
{
    return $this->attributes['harga_per_kg'] ?? $this->attributes['harga_perkg'] ?? null;
}

public function setHargaPerKgAttribute($value)
{
    $this->attributes['harga_per_kg'] = $value;
}

public function getDeskripsiAttribute()
{
    if (array_key_exists('deskripsi', $this->attributes)) {
        return $this->attributes['deskripsi'];
    }

    return ! empty($this->attributes['estimasi_hari']) ? $this->attributes['estimasi_hari'] . ' hari' : null;
}
}
