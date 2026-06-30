<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Notifikasi extends Model
{
    use HasFactory;

    protected $table = 'notifikasi';
    protected $fillable = [
        'pelanggan_id',
        'error_report_id',
        'pesan',
        'status_baca'
    ];

    public function pelanggan()
    {
        return $this->belongsTo(Pelanggan::class, 'pelanggan_id');
    }

    public function errorReport()
    {
        return $this->belongsTo(ErrorReport::class, 'error_report_id');
    }
}
