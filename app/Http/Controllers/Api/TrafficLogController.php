<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\TrafficLog;
use Illuminate\Support\Facades\DB;
use Illuminate\Http\Request;

class TrafficLogController extends Controller
{
    public function storeLog(Request $request)
    {
        $validated = $request->validate([
            'cctv_id' => ['required', 'integer', 'exists:cctvs,id'],
            'batch_id' => ['nullable', 'uuid'],
            'logs' => ['required_without:arah', 'array', 'min:1', 'max:2'],
            'logs.*.arah' => ['required', 'in:Normal,Opposite', 'distinct'],
            'logs.*.sepeda_motor' => ['required', 'integer', 'min:0'],
            'logs.*.mobil_penumpang' => ['required', 'integer', 'min:0'],
            'logs.*.kendaraan_sedang' => ['required', 'integer', 'min:0'],
            'logs.*.bus_besar' => ['required', 'integer', 'min:0'],
            'logs.*.truk_barang' => ['required', 'integer', 'min:0'],
            'arah' => ['required_without:logs', 'in:Normal,Opposite'],
            'sepeda_motor' => ['nullable', 'integer', 'min:0'],
            'mobil_penumpang' => ['nullable', 'integer', 'min:0'],
            'kendaraan_sedang' => ['nullable', 'integer', 'min:0'],
            'bus_besar' => ['nullable', 'integer', 'min:0'],
            'truk_barang' => ['nullable', 'integer', 'min:0'],
        ]);

        if (isset($validated['logs'])) {
            $timestamp = now();
            $records = collect($validated['logs'])->map(fn (array $log) => [
                ...$log,
                'cctv_id' => $validated['cctv_id'],
                'batch_id' => $validated['batch_id'] ?? null,
                'created_at' => $timestamp,
                'updated_at' => $timestamp,
            ])->all();

            $savedCount = DB::transaction(fn () => TrafficLog::insertOrIgnore($records));

            return response()->json([
                'message' => 'Rekap lalu lintas berhasil disimpan!',
                'saved_count' => $savedCount,
            ], $savedCount > 0 ? 201 : 200);
        }

        $log = TrafficLog::create([
            ...$validated,
            'cctv_id' => $validated['cctv_id'],
        ]);

        return response()->json([
            'message' => 'Data log kendaraan berhasil disimpan!',
            'data' => $log
        ], 200);
    }
}