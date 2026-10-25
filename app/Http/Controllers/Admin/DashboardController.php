<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Cctv;
use App\Models\Jalan;

class DashboardController extends Controller
{
    public function index()
    {
        $stats = [
            'jalan' => Jalan::count(),
            'cctv' => Cctv::count(),
            'macet' => Cctv::where('kondisi_terkini', 'Macet')->count(),
            'banjir' => Cctv::where('status_banjir', 'Banjir')->count(),
            'peringatan' => Cctv::where(function ($query) {
                $query->where('kondisi_terkini', 'Macet')
                    ->orWhere('status_banjir', 'Banjir');
            })->count(),
        ];

        $alerts = Cctv::with('jalan')
            ->where(function ($query) {
                $query->where('kondisi_terkini', 'Macet')
                    ->orWhere('status_banjir', 'Banjir');
            })
            ->latest('updated_at')
            ->take(6)
            ->get();

        $recentCctvs = Cctv::with('jalan')
            ->latest('updated_at')
            ->take(6)
            ->get();

        return view('admin.dashboard', compact('stats', 'alerts', 'recentCctvs'));
    }
}
