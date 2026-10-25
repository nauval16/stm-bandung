<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Cctv;
use App\Models\Jalan;
use App\Models\TrafficLog;
use Carbon\Carbon;

class HomeController extends Controller
{
    public function index()
    {
        $stats = [
            'cctv' => Cctv::count(),
            'jalan' => Jalan::count(),
            'banjir' => Cctv::where('status_banjir', 'Banjir')->count(),
            'macet' => Cctv::where('kondisi_terkini', 'Macet')->count(),
        ];

        $cctvs = Cctv::with('jalan')->latest()->take(3)->get();

        return view('public.home', compact('stats', 'cctvs'));
    }

    public function tentang()
    {
        // Pastikan nama file blade-nya nanti 'tentang.blade.php'
        return view('public.tentang-kami'); 
    }

    // Fungsi untuk halaman Daftar CCTV
    public function cctv()
    {
        $cctvs = Cctv::with('jalan')->latest()->get();
        $orderedCctvIds = Cctv::orderBy('id')->pluck('id')->all();
        $aiStreamUrls = $cctvs->mapWithKeys(function (Cctv $cctv) use ($orderedCctvIds) {
            $index = array_search($cctv->id, $orderedCctvIds);

            return [$cctv->id => $this->aiStreamUrl(
                $cctv->id,
                $index === false ? 0 : $index
            )];
        });
        $mapCctvs = $cctvs
            ->filter(fn ($cctv) => is_numeric($cctv->latitude) && is_numeric($cctv->longitude))
            ->map(fn ($cctv) => [
                'id' => $cctv->id,
                'nama_jalan' => $cctv->jalan->nama_jalan ?? 'Lokasi belum diatur',
                'latitude' => (float) $cctv->latitude,
                'longitude' => (float) $cctv->longitude,
                'kondisi_terkini' => $cctv->kondisi_terkini,
                'status_banjir' => $cctv->status_banjir,
                'jumlah_kendaraan' => $cctv->jumlah_kendaraan,
                'ketinggian_air_cm' => $cctv->ketinggian_air_cm,
                'ai_connection_status' => $cctv->aiConnectionStatus(),
            ])
            ->values();

        $stats = [
            'total' => $cctvs->count(),
            'macet' => $cctvs->where('kondisi_terkini', 'Macet')->count(),
            'banjir' => $cctvs->where('status_banjir', 'Banjir')->count(),
            'aman' => $cctvs->filter(fn ($cctv) => $cctv->kondisi_terkini === 'Aman' && $cctv->status_banjir === 'Aman')->count(),
        ];

        return view('public.cctv', compact('cctvs', 'mapCctvs', 'aiStreamUrls', 'stats'));
    }

    // Menampilkan Halaman Peta Rute Cerdas
    public function rute()
    {
        // Hanya ambil CCTV yang sudah memiliki titik koordinat
        $cctvs = Cctv::with('jalan')
            ->whereNotNull('latitude')
            ->whereNotNull('longitude')
            ->get();

        $cctvs->each(function (Cctv $cctv) {
            $cctv->setAttribute('ai_connection_status', $cctv->aiConnectionStatus());
        });
            
        return view('public.rute', compact('cctvs'));
    }

    public function showDetail($id)
    {
        $cctv = Cctv::with('jalan')->findOrFail($id);
        $orderedCctvIds = Cctv::orderBy('id')->pluck('id')->all();
        $streamIndex = array_search($cctv->id, $orderedCctvIds);
        $aiStreamUrl = $this->aiStreamUrl(
            $cctv->id,
            $streamIndex === false ? 0 : $streamIndex
        );
        
        $today = Carbon::today();
        $now = Carbon::now();

        // ==========================================
        // A. DATA HARIAN (TOTAL HARI INI)
        // ==========================================
        $dailyLogs = TrafficLog::where('cctv_id', $id)->whereDate('created_at', $today)->get();
        $daily = $this->aggregateLogs($dailyLogs);

        // ==========================================
        // B. DATA 5 MENIT TERAKHIR
        // ==========================================
        $fiveMinuteLogs = TrafficLog::where('cctv_id', $id)
            ->where('created_at', '>=', $now->copy()->subMinutes(5))
            ->get();
        $fiveMinute = $this->aggregateLogs($fiveMinuteLogs);

        // ==========================================
        // C. DATA 5 MENITAN (UNTUK BAR CHART 1 JAM TERAKHIR)
        // ==========================================
        $lastHourLogs = TrafficLog::where('cctv_id', $id)
            ->where('created_at', '>=', $now->copy()->subHour())
            ->get();
        
        $chartCategories = [];
        $chartNormal = [];
        $chartOpposite = [];

        for ($i = 11; $i >= 0; $i--) {
            $start = $now->copy()->subMinutes(($i + 1) * 5);
            $end = $now->copy()->subMinutes($i * 5);
            
            $chartCategories[] = $end->format('H:i');
            
            $chartNormal[] = $lastHourLogs->where('arah', 'Normal')->filter(function($log) use ($start, $end) {
                return $log->created_at->between($start, $end);
            })->sum(function($row) { 
                return $row->sepeda_motor + $row->mobil_penumpang + $row->kendaraan_sedang + $row->bus_besar + $row->truk_barang; 
            });
            
            $chartOpposite[] = $lastHourLogs->where('arah', 'Opposite')->filter(function($log) use ($start, $end) {
                return $log->created_at->between($start, $end);
            })->sum(function($row) { 
                return $row->sepeda_motor + $row->mobil_penumpang + $row->kendaraan_sedang + $row->bus_besar + $row->truk_barang; 
            });
        }

        return view('public.cctv-detail', compact('cctv', 'aiStreamUrl', 'daily', 'fiveMinute', 'chartCategories', 'chartNormal', 'chartOpposite'));
    }

    private function aiStreamUrl(int $cctvId, int $index): string
    {
        $port = (int) config('services.stm.ai_stream_base_port', 5000) + $cctvId;
        $template = config('services.stm.ai_stream_url_template', 'http://127.0.0.1:{port}/video_ai');

        return strtr($template, [
            '{id}' => (string) $cctvId,
            '{index}' => (string) $index,
            '{port}' => (string) $port,
        ]);
    }

    // Fungsi pembantu agar kodenya tidak panjang & berulang
    private function aggregateLogs($logs)
    {
        $sumVehicles = function ($row) {
            return $row->sepeda_motor + $row->mobil_penumpang + $row->kendaraan_sedang + $row->bus_besar + $row->truk_barang;
        };

        return [
            'normal' => [
                'sepeda_motor'     => $logs->where('arah', 'Normal')->sum('sepeda_motor'),
                'mobil_penumpang'  => $logs->where('arah', 'Normal')->sum('mobil_penumpang'),
                'kendaraan_sedang' => $logs->where('arah', 'Normal')->sum('kendaraan_sedang'),
                'bus_besar'        => $logs->where('arah', 'Normal')->sum('bus_besar'),
                'truk_barang'      => $logs->where('arah', 'Normal')->sum('truk_barang'),
                'total'            => $logs->where('arah', 'Normal')->sum($sumVehicles)
            ],
            'opposite' => [
                'sepeda_motor'     => $logs->where('arah', 'Opposite')->sum('sepeda_motor'),
                'mobil_penumpang'  => $logs->where('arah', 'Opposite')->sum('mobil_penumpang'),
                'kendaraan_sedang' => $logs->where('arah', 'Opposite')->sum('kendaraan_sedang'),
                'bus_besar'        => $logs->where('arah', 'Opposite')->sum('bus_besar'),
                'truk_barang'      => $logs->where('arah', 'Opposite')->sum('truk_barang'),
                'total'            => $logs->where('arah', 'Opposite')->sum($sumVehicles)
            ]
        ];
    }
}