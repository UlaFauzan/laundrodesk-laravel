<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class ErrorReport extends Model
{
    protected $fillable = [
        'user_id',
        'pelanggan_id',
        'page_name',
        'error_message',
        'description',
        'error_details',
        'resolved_at',
        'admin_note',
    ];

    protected $casts = [
        'error_details' => 'array',
        'resolved_at' => 'datetime',
    ];

    public function user()
    {
        return $this->belongsTo(User::class);
    }

    public function pelanggan()
    {
        return $this->belongsTo(Pelanggan::class);
    }
}
