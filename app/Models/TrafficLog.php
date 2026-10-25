<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class TrafficLog extends Model
{
    use HasFactory;

    protected $guarded = []; // Mengizinkan semua kolom diisi oleh API

    // Relasi balik ke CCTV
    public function cctv()
    {
        return $this->belongsTo(Cctv::class);
    }
}