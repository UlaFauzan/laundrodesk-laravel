<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Pelanggan extends Model
{
    use HasFactory;

    protected $table = 'pelanggan';
    protected $fillable = [
        'nama',
        'telepon',
        'alamat',
        'nama_pelanggan',
        'no_hp',
    ];

public function transaksi()
{
    return $this->hasMany(Transaksi::class, 'pelanggan_id');
}

public function getIdAttribute()
{
    return $this->attributes[$this->getKeyName()] ?? null;
}

public function getNamaAttribute()
{
    return $this->attributes['nama'] ?? $this->attributes['nama_pelanggan'] ?? null;
}

public function setNamaAttribute($value)
{
    $this->attributes['nama'] = $value;
}

public function getTeleponAttribute()
{
    return $this->attributes['telepon'] ?? $this->attributes['no_hp'] ?? null;
}

public function setTeleponAttribute($value)
{
    $this->attributes['telepon'] = $value;
}
}
