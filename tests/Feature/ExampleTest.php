<?php

namespace Tests\Feature;

use App\Models\Cctv;
use App\Models\Jalan;
use App\Models\TrafficLog;
use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class ExampleTest extends TestCase
{
    use RefreshDatabase;

    /**
     * A basic test example.
     */
    public function test_the_application_returns_a_successful_response(): void
    {
        $response = $this->get('/');

        $response->assertStatus(200);
    }

    public function test_cctv_dashboard_shows_camera_status_and_mapped_location(): void
    {
        $jalan = Jalan::create([
            'nama_jalan' => 'Bojong Soang',
            'kecamatan' => 'Bojongsoang',
        ]);

        Cctv::create([
            'jalan_id' => $jalan->id,
            'latitude' => '-6.9',
            'longitude' => '107.6',
            'kondisi_terkini' => 'Aman',
            'jumlah_kendaraan' => 3,
            'status_banjir' => 'Banjir',
            'ketinggian_air_cm' => 4,
        ]);

        $response = $this->get('/cctv');

        $response->assertOk()
            ->assertSee('Peta sebaran CCTV')
            ->assertSee('Bojong Soang')
            ->assertSee('AI Traffic')
            ->assertSee('Aman')
            ->assertSee('IoT Sensor')
            ->assertSee('Banjir')
            ->assertSee('Tinggi air:');
    }

    public function test_ai_stream_urls_use_cctv_ids_to_match_python_server_ports(): void
    {
        config([
            'services.stm.ai_stream_base_port' => 5000,
            'services.stm.ai_stream_url_template' => 'http://127.0.0.1:{port}/video_ai/{id}/{index}',
        ]);

        $jalan = Jalan::create([
            'nama_jalan' => 'Bojong Soang',
            'kecamatan' => 'Bojongsoang',
        ]);

        $firstCctv = Cctv::create(['jalan_id' => $jalan->id]);
        $secondCctv = Cctv::create(['jalan_id' => $jalan->id]);
        $thirdCctv = Cctv::create(['jalan_id' => $jalan->id]);

        $this->get('/cctv')
            ->assertOk()
            ->assertSee(
                "data-ai-stream-url=\"http://127.0.0.1:5001/video_ai/{$firstCctv->id}/0\"",
                false
            )
            ->assertSee(
                "data-ai-stream-url=\"http://127.0.0.1:5002/video_ai/{$secondCctv->id}/1\"",
                false
            )
            ->assertSee(
                "data-ai-stream-url=\"http://127.0.0.1:5003/video_ai/{$thirdCctv->id}/2\"",
                false
            );

        $this->get("/cctv/detail/{$thirdCctv->id}")
            ->assertOk()
            ->assertSee("http:\\/\\/127.0.0.1:5003\\/video_ai\\/{$thirdCctv->id}\\/2", false);
    }

    public function test_admin_dashboard_shows_live_counts_and_active_alerts(): void
    {
        $jalan = Jalan::create([
            'nama_jalan' => 'Bojong Soang',
            'kecamatan' => 'Bojongsoang',
        ]);

        Cctv::create([
            'jalan_id' => $jalan->id,
            'kondisi_terkini' => 'Macet',
            'jumlah_kendaraan' => 12,
            'status_banjir' => 'Banjir',
            'ketinggian_air_cm' => 8,
        ]);

        $response = $this->actingAs(User::factory()->create())->get('/admin/dashboard');

        $response->assertOk()
            ->assertSee('Dashboard Admin STM')
            ->assertSee('Bojong Soang')
            ->assertSee('Macet · 12 kendaraan')
            ->assertSee('Banjir · 8 cm');
    }

    public function test_map_status_api_returns_latest_status_for_cctvs_with_coordinates(): void
    {
        $jalan = Jalan::create([
            'nama_jalan' => 'Bojong Soang',
            'kecamatan' => 'Bojongsoang',
        ]);

        Cctv::create([
            'jalan_id' => $jalan->id,
            'latitude' => '-6.9',
            'longitude' => '107.6',
            'kondisi_terkini' => 'Macet',
            'status_banjir' => 'Banjir',
        ]);

        $response = $this->getJson('/api/cctv-sensor/map-status');

        $response->assertOk()
            ->assertJsonCount(1)
            ->assertJsonPath('0.kondisi_terkini', 'Macet')
            ->assertJsonPath('0.status_banjir', 'Banjir')
            ->assertJsonPath('0.ai_connection_status', 'waiting')
            ->assertJsonPath('0.jalan.nama_jalan', 'Bojong Soang');
    }

    public function test_ai_updates_mark_the_server_connected_and_expose_last_seen_time(): void
    {
        $jalan = Jalan::create([
            'nama_jalan' => 'Bojong Soang',
            'kecamatan' => 'Bojongsoang',
        ]);

        $cctv = Cctv::create(['jalan_id' => $jalan->id]);

        $this->postJson('/api/cctv-sensor/update', [
            'cctv_id' => $cctv->id,
            'kondisi_terkini' => 'Aman',
            'jumlah_kendaraan' => 3,
        ])->assertOk();

        $this->getJson("/api/cctv-sensor/status/{$cctv->id}")
            ->assertOk()
            ->assertJsonPath('ai_connection_status', 'connected')
            ->assertJsonStructure(['ai_last_seen_at']);

        $this->assertNotNull($cctv->fresh()->ai_last_seen_at);
    }

    public function test_old_ai_updates_are_reported_as_stale(): void
    {
        $jalan = Jalan::create([
            'nama_jalan' => 'Bojong Soang',
            'kecamatan' => 'Bojongsoang',
        ]);

        $cctv = Cctv::create([
            'jalan_id' => $jalan->id,
            'ai_last_seen_at' => now()->subSeconds(61),
        ]);

        $this->getJson("/api/cctv-sensor/status/{$cctv->id}")
            ->assertOk()
            ->assertJsonPath('ai_connection_status', 'stale');
    }

    public function test_iot_dummy_updates_do_not_mark_the_ai_server_connected(): void
    {
        $jalan = Jalan::create([
            'nama_jalan' => 'Bojong Soang',
            'kecamatan' => 'Bojongsoang',
        ]);

        $cctv = Cctv::create(['jalan_id' => $jalan->id]);

        $this->postJson('/api/cctv-sensor/update', [
            'cctv_id' => $cctv->id,
            'status_banjir' => 'Banjir',
            'ketinggian_air_cm' => 4,
        ])->assertOk();

        $this->getJson("/api/cctv-sensor/status/{$cctv->id}")
            ->assertOk()
            ->assertJsonPath('status_banjir', 'Banjir')
            ->assertJsonPath('ai_connection_status', 'waiting');
    }

    public function test_traffic_log_api_accepts_both_directions_in_one_five_minute_batch(): void
    {
        $jalan = Jalan::create([
            'nama_jalan' => 'Bojong Soang',
            'kecamatan' => 'Bojongsoang',
        ]);

        $cctv = Cctv::create(['jalan_id' => $jalan->id]);
        $directionData = [
            'sepeda_motor' => 10,
            'mobil_penumpang' => 5,
            'kendaraan_sedang' => 0,
            'bus_besar' => 1,
            'truk_barang' => 2,
        ];

        $this->postJson('/api/cctv-sensor/traffic-log', [
            'cctv_id' => $cctv->id,
            'batch_id' => 'd9c68fe2-558b-47e8-a630-e3f75da7d35a',
            'logs' => [
                ['arah' => 'Normal', ...$directionData],
                ['arah' => 'Opposite', ...$directionData],
            ],
        ])->assertCreated()
            ->assertJsonPath('saved_count', 2);

        $this->postJson('/api/cctv-sensor/traffic-log', [
            'cctv_id' => $cctv->id,
            'batch_id' => 'd9c68fe2-558b-47e8-a630-e3f75da7d35a',
            'logs' => [
                ['arah' => 'Normal', ...$directionData],
                ['arah' => 'Opposite', ...$directionData],
            ],
        ])->assertOk()
            ->assertJsonPath('saved_count', 0);

        $this->assertSame(2, TrafficLog::where('cctv_id', $cctv->id)->count());
        $this->assertDatabaseHas('traffic_logs', [
            'cctv_id' => $cctv->id,
            'arah' => 'Normal',
            'sepeda_motor' => 10,
        ]);
        $this->assertDatabaseHas('traffic_logs', [
            'cctv_id' => $cctv->id,
            'arah' => 'Opposite',
            'sepeda_motor' => 10,
        ]);
    }

    public function test_traffic_log_api_keeps_the_existing_single_direction_payload(): void
    {
        $jalan = Jalan::create([
            'nama_jalan' => 'Bojong Soang',
            'kecamatan' => 'Bojongsoang',
        ]);

        $cctv = Cctv::create(['jalan_id' => $jalan->id]);

        $this->postJson('/api/cctv-sensor/traffic-log', [
            'cctv_id' => $cctv->id,
            'arah' => 'Normal',
            'sepeda_motor' => 4,
            'mobil_penumpang' => 2,
            'kendaraan_sedang' => 0,
            'bus_besar' => 0,
            'truk_barang' => 0,
        ])->assertOk();

        $this->assertSame(1, TrafficLog::where('cctv_id', $cctv->id)->count());
    }

    public function test_cctv_details_show_the_five_minute_analytics_window(): void
    {
        $jalan = Jalan::create([
            'nama_jalan' => 'Bojong Soang',
            'kecamatan' => 'Bojongsoang',
        ]);

        $cctv = Cctv::create(['jalan_id' => $jalan->id]);

        TrafficLog::create([
            'cctv_id' => $cctv->id,
            'arah' => 'Normal',
            'sepeda_motor' => 7,
            'mobil_penumpang' => 2,
            'kendaraan_sedang' => 0,
            'bus_besar' => 0,
            'truk_barang' => 0,
            'created_at' => now()->subMinutes(2),
        ]);

        TrafficLog::create([
            'cctv_id' => $cctv->id,
            'arah' => 'Normal',
            'sepeda_motor' => 99,
            'mobil_penumpang' => 0,
            'kendaraan_sedang' => 0,
            'bus_besar' => 0,
            'truk_barang' => 0,
            'created_at' => now()->subMinutes(7),
        ]);

        $response = $this->get("/cctv/detail/{$cctv->id}");

        $response->assertOk()
            ->assertSee('Volume Kendaraan 5 Menit Terakhir')
            ->assertDontSee('Volume Kendaraan 1 Menit Terakhir')
            ->assertViewHas('fiveMinute', fn (array $data) =>
                $data['normal']['motor'] === 7 && $data['normal']['total'] === 9
            );
    }

    public function test_route_map_page_loads_live_status_refresh_logic(): void
    {
        $response = $this->get('/rute-cerdas');

        $response->assertOk()
            ->assertSee('/api/cctv-sensor/map-status')
            ->assertSee('Pembaruan otomatis aktif')
        ->assertSee('window.setInterval(refreshCctvStatuses, 15000)');
    }
}
