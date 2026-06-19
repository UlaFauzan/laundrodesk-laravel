<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class StatusLaundry extends Model
{
    use HasFactory;

    protected $table = 'status_laundry';
    protected $fillable = [
        'nama_status',
    ];
    public function transaksi()
    {
    return $this->hasMany(Transaksi::class, 'status_laundry_id');
    }

    public function getIdAttribute()
    {
        return $this->attributes[$this->getKeyName()] ?? null;
    }

    public function getNamaStatusAttribute()
    {
        return $this->attributes['nama_status'] ?? $this->attributes['status'] ?? null;
    }
}
