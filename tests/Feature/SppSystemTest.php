<?php

namespace Tests\Feature;

use Tests\TestCase;
use App\Models\User;
use App\Models\Siswa;
use App\Models\Kelas;
use App\Models\Spp;
use App\Models\Tagihan;
use App\Services\TagihanService;
use App\Services\PembayaranService;
use Illuminate\Foundation\Testing\RefreshDatabase;

class SppSystemTest extends TestCase
{
    use RefreshDatabase;

    protected function setUp(): void
    {
        parent::setUp();

        // Seed admin
        $this->admin = User::create([
            'username' => 'admin',
            'password' => bcrypt('admin123'),
            'role' => 'admin',
            'is_first_login' => false,
            'is_active' => true,
        ]);

        // Seed kelas
        $this->kelas = Kelas::create([
            'nama_kelas' => 'X-A',
            'tingkat' => 'X',
            'tahun_ajaran' => '2025/2026',
        ]);

        // Seed SPP
        $this->spp = Spp::create([
            'kelas_id' => $this->kelas->id,
            'nominal' => 250000,
            'tahun_ajaran' => '2025/2026',
            'berlaku_mulai' => '2025-07-01',
        ]);

        // Seed siswa
        $this->siswaUser = User::create([
            'username' => '10001',
            'password' => bcrypt('01012008'),
            'role' => 'siswa',
            'is_first_login' => true,
            'is_active' => true,
        ]);

        $this->siswa = Siswa::create([
            'user_id' => $this->siswaUser->id,
            'kelas_id' => $this->kelas->id,
            'nis' => '10001',
            'nama' => 'Test Siswa',
            'tanggal_lahir' => '2008-01-01',
            'jenis_kelamin' => 'L',
        ]);
    }

    // =====================
    // Authentication Tests
    // =====================

    public function test_login_page_loads()
    {
        $response = $this->get('/login');
        $response->assertStatus(200);
        $response->assertSee('SPP Sekolah');
    }

    public function test_admin_can_login()
    {
        $response = $this->post('/login', [
            'username' => 'admin',
            'password' => 'password',
        ]);
        $response->assertRedirect('/admin/dashboard');
        $this->assertAuthenticatedAs($this->admin);
    }

    public function test_login_fails_with_wrong_password()
    {
        $response = $this->post('/login', [
            'username' => 'admin',
            'password' => 'wrongpassword',
        ]);
        $response->assertSessionHasErrors();
        $this->assertGuest();
    }

    public function test_siswa_first_login_redirect_to_change_password()
    {
        $response = $this->actingAs($this->siswaUser)
            ->get('/siswa/dashboard');
        $response->assertRedirect('/ganti-password');
    }

    public function test_admin_dashboard_accessible()
    {
        $response = $this->actingAs($this->admin)->get('/admin/dashboard');
        $response->assertStatus(200);
        $response->assertSee('Dashboard');
    }

    // =====================
    // Tagihan Service Tests
    // =====================

    public function test_generate_tagihan_creates_records()
    {
        $service = new TagihanService();
        $count = $service->generateBulanan(2, 2026);

        $this->assertEquals(1, $count);
        $this->assertDatabaseHas('tagihan', [
            'siswa_id' => $this->siswa->id,
            'bulan' => 2,
            'tahun' => 2026,
            'status' => 'belum_bayar',
        ]);
    }

    public function test_generate_tagihan_no_duplicate()
    {
        $service = new TagihanService();
        $service->generateBulanan(2, 2026);
        $count = $service->generateBulanan(2, 2026); // Run again

        $this->assertEquals(0, $count); // No duplicates
        $this->assertEquals(1, Tagihan::where('bulan', 2)->where('tahun', 2026)->count());
    }

    public function test_generate_tagihan_sends_notification()
    {
        $service = new TagihanService();
        $service->generateBulanan(2, 2026);

        $this->assertDatabaseHas('notifikasi', [
            'user_id' => $this->siswaUser->id,
        ]);
    }

    // =====================
    // Pembayaran Service Tests
    // =====================

    public function test_verifikasi_pembayaran()
    {
        $tagihan = Tagihan::create([
            'siswa_id' => $this->siswa->id,
            'spp_id' => $this->spp->id,
            'bulan' => 3,
            'tahun' => 2026,
            'nominal' => 250000,
            'status' => 'menunggu_verifikasi',
        ]);

        $tagihan->pembayaran()->create([
            'file_bukti' => 'test.jpg',
            'tanggal_upload' => now(),
        ]);

        $this->actingAs($this->admin);

        $service = new PembayaranService();
        $service->verifikasi($tagihan);

        $tagihan->refresh();
        $this->assertEquals('lunas', $tagihan->status);
        $this->assertNotNull($tagihan->pembayaran->tanggal_verifikasi);
    }

    public function test_tolak_pembayaran()
    {
        $tagihan = Tagihan::create([
            'siswa_id' => $this->siswa->id,
            'spp_id' => $this->spp->id,
            'bulan' => 4,
            'tahun' => 2026,
            'nominal' => 250000,
            'status' => 'menunggu_verifikasi',
        ]);

        $tagihan->pembayaran()->create([
            'file_bukti' => 'test.jpg',
            'tanggal_upload' => now(),
        ]);

        $service = new PembayaranService();
        $service->tolak($tagihan, 'Bukti tidak jelas');

        $tagihan->refresh();
        $this->assertEquals('ditolak', $tagihan->status);
        $this->assertEquals('Bukti tidak jelas', $tagihan->pembayaran->catatan);
    }

    // =====================
    // Route Protection Tests
    // =====================

    public function test_guest_cannot_access_admin_dashboard()
    {
        $response = $this->get('/admin/dashboard');
        $response->assertRedirect('/login');
    }

    public function test_siswa_cannot_access_admin_routes()
    {
        $this->siswaUser->update(['is_first_login' => false]);
        $response = $this->actingAs($this->siswaUser)->get('/admin/dashboard');
        $response->assertStatus(403);
    }

    public function test_admin_cannot_access_siswa_routes()
    {
        $response = $this->actingAs($this->admin)->get('/siswa/dashboard');
        $response->assertStatus(403);
    }

    // =====================
    // Dashboard Data Tests
    // =====================

    public function test_dashboard_shows_chart_data()
    {
        $service = new TagihanService();
        $stats = $service->getDashboardStats();

        $this->assertArrayHasKey('totalSiswa', $stats);
        $this->assertArrayHasKey('pembayaranHariIni', $stats);
        $this->assertArrayHasKey('menungguVerifikasi', $stats);
        $this->assertEquals(1, $stats['totalSiswa']);

        $tren = $service->getTrenPembayaran();
        $this->assertArrayHasKey('labels', $tren);
        $this->assertArrayHasKey('lunas', $tren);
        $this->assertCount(6, $tren['labels']);
    }
}
