<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\Cctv;


class CctvSensorController extends Controller
{
    // Fungsi untuk menerima kiriman data dari NodeMCU/ESP32 dan Python AI
    public function updateStatus(Request $request)
    {
        // Validasi dibuat "nullable" agar fleksibel
        $request->validate([
            'cctv_id'           => 'required|integer',
            'kondisi_terkini'   => 'nullable|string',
            'jumlah_kendaraan'  => 'nullable|integer',
            'status_banjir'     => 'nullable|string',
            'ketinggian_air_cm' => 'nullable|integer'
        ]);

        $cctv = Cctv::find($request->cctv_id);

        if (!$cctv) {
            return response()->json(['message' => 'CCTV tidak ditemukan'], 404);
        }

        // Jika data dikirim oleh AI Python
        if ($request->has('kondisi_terkini')) {
            $cctv->kondisi_terkini = $request->kondisi_terkini;
        }
        if ($request->has('jumlah_kendaraan')) {
            $cctv->jumlah_kendaraan = $request->jumlah_kendaraan;
        }
        if ($request->hasAny(['kondisi_terkini', 'jumlah_kendaraan'])) {
            $cctv->ai_last_seen_at = now();
        }

        // Jika data dikirim oleh Alat IoT (NodeMCU/ESP32)
        if ($request->has('status_banjir')) {
            $cctv->status_banjir = $request->status_banjir;
        }
        if ($request->has('ketinggian_air_cm')) {
            $cctv->ketinggian_air_cm = $request->ketinggian_air_cm;
        }

        $cctv->save();

        return response()->json([
            'message' => 'Status CCTV & Sensor berhasil diperbarui!',
            'data' => $cctv
        ], 200);
    }

    // Fungsi untuk diambil datanya oleh Frontend (Command Center Warga)
    public function getStatus($id)
    {
        $cctv = Cctv::find($id); 

        if (!$cctv) {
            return response()->json(['message' => 'CCTV tidak ditemukan'], 404);
        }

        return response()->json([
            'kondisi_terkini'   => $cctv->kondisi_terkini,
            'jumlah_kendaraan'  => $cctv->jumlah_kendaraan,
            'ai_connection_status' => $cctv->aiConnectionStatus(),
            'ai_last_seen_at' => $cctv->ai_last_seen_at?->toIso8601String(),
            'status_banjir'     => $cctv->status_banjir,
            'ketinggian_air_cm' => $cctv->ketinggian_air_cm 
        ], 200)->header('Cache-Control', 'no-store');
    }

    public function getListCctv()
    {
        $cctvs = Cctv::select('id', 'link_akses')->orderBy('id')->get();
        return response()->json($cctvs, 200);
    }

    public function getMapStatus()
    {
        $cctvs = Cctv::with('jalan:id,nama_jalan')
            ->whereNotNull('latitude')
            ->whereNotNull('longitude')
            ->orderBy('id')
            ->get([
                'id',
                'jalan_id',
                'latitude',
                'longitude',
                'gambar',
                'kondisi_terkini',
                'jumlah_kendaraan',
                'ai_last_seen_at',
                'status_banjir',
                'ketinggian_air_cm',
            ])
            ->map(function (Cctv $cctv) {
                $cctv->setAttribute('ai_connection_status', $cctv->aiConnectionStatus());

                return $cctv;
            });

        return response()->json($cctvs)->header('Cache-Control', 'no-store');
    }
}