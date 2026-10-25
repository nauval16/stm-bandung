<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Carbon;

class Cctv extends Model
{
    protected function casts(): array
    {
        return [
            'ai_last_seen_at' => 'datetime',
        ];
    }

    protected $fillable = [
        'jalan_id', 'gambar', 'link_akses', 
        'latitude', 'longitude', // <-- Tambahkan ini
        'kondisi_terkini', 'jumlah_kendaraan', 'ai_last_seen_at',
        'status_banjir', 'ketinggian_air_cm'
    ];

    public function jalan()
    {
        return $this->belongsTo(Jalan::class);
    }

    public function trafficLogs()
    {
        return $this->hasMany(TrafficLog::class);
    }

    public function aiConnectionStatus(): string
    {
        if (!$this->ai_last_seen_at) {
            return 'waiting';
        }

        $lastSeen = Carbon::instance($this->ai_last_seen_at);
        $staleAfter = max(1, (int) config('services.stm.ai_stale_after_seconds', 30));

        return !$lastSeen->isFuture() && $lastSeen->diffInSeconds(now()) <= $staleAfter
            ? 'connected'
            : 'stale';
    }
}
