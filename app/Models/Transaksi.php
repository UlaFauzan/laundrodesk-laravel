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
        'kode_transaksi',
        'pelanggan_id',
        'user_id',
        'layanan_id',
        'berat_kg',
        'status',
        'tanggal_masuk',
        'tanggal_selesai',
        'status_laundry_id',
        'tanggal_transaksi',
        'total_berat',
        'total_harga',
        'status_pembayaran',
        'status_laundry',
    ];

    public function pelanggan()
    {
        return $this->belongsTo(Pelanggan::class, 'pelanggan_id');
    }

    public function layanan()
    {
        return $this->belongsTo(Layanan::class, 'layanan_id');
    }

    public function statusLaundry()
    {
        return $this->belongsTo(StatusLaundry::class, 'status_laundry_id');
    }

    public function pembayaran()
    {
        return $this->hasOne(Pembayaran::class, 'transaksi_id');
    }

    public function tambahPoin()
    {
        return $this->hasMany(TambahPoin::class, 'transaksi_id');
    }
    
    public function detailTransaksi()
    {
        return $this->hasMany(DetailTransaksi::class, 'transaksi_id');
    }

    public function getIdAttribute()
    {
        return $this->attributes[$this->getKeyName()] ?? null;
    }

    public function getBeratKgAttribute()
    {
        return $this->attributes['berat_kg'] ?? $this->attributes['total_berat'] ?? null;
    }

    public function setBeratKgAttribute($value)
    {
        $this->attributes['berat_kg'] = $value;
    }

    public function getStatusAttribute()
    {
        return $this->attributes['status'] ?? $this->attributes['status_laundry'] ?? null;
    }

    public function setStatusAttribute($value)
    {
        $this->attributes['status'] = $value;
    }

    public function getTanggalMasukAttribute()
    {
        return $this->attributes['tanggal_masuk'] ?? $this->attributes['tanggal_transaksi'] ?? null;
    }

    public function setTanggalMasukAttribute($value)
    {
        $this->attributes['tanggal_masuk'] = $value;
    }

    public function getTanggalSelesaiAttribute()
    {
        return $this->attributes['tanggal_selesai'] ?? null;
    }

    // Method untuk mengetahui status pembayaran sebenarnya (lunas/hutang/belum bayar)
    public function getStatusPembayaranSebenarnya()
    {
        if (!$this->pembayaran) {
            return $this->total_harga > 0 ? 'hutang' : 'Belum Bayar';
        }

        $sisa = $this->total_harga - $this->pembayaran->jumlah_bayar;
        
        if ($sisa <= 0) {
            return 'lunas';
        } else {
            return 'hutang';
        }
    }

    public function syncStatusPembayaran()
    {
        $status = $this->getStatusPembayaranSebenarnya();

        if ($this->status_pembayaran !== $status) {
            $this->status_pembayaran = $status;
            $this->saveQuietly();
        }

        return $status;
    }

    // Method untuk mengetahui sisa pembayaran
    public function getSisaPembayaran()
    {
        if (!$this->pembayaran) {
            return $this->total_harga;
        }
        
        $sisa = $this->total_harga - $this->pembayaran->jumlah_bayar;
        return max(0, $sisa);
    }
}
