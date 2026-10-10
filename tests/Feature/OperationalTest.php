<?php

namespace Tests\Feature;

use Illuminate\Support\Facades\Route;
use Tests\TestCase;

class OperationalTest extends TestCase
{
    public function test_health_check_endpoint_returns_ok(): void
    {
        $this->get('/up')->assertOk();
    }

    public function test_timezone_is_asia_jakarta_from_a_single_config_value(): void
    {
        $this->assertSame('Asia/Jakarta', config('app.timezone'));
        $this->assertSame('Asia/Jakarta', now()->timezoneName);
    }

    public function test_not_found_page_is_indonesian_and_has_no_stack_trace(): void
    {
        config(['app.debug' => false]);

        $this->get('/halaman-tidak-ada')
            ->assertNotFound()
            ->assertSee('Halaman tidak ditemukan')
            ->assertDontSee('Stack trace', false)
            ->assertDontSee('vendor/laravel', false);
    }

    public function test_server_error_hides_exception_details_when_debug_is_off(): void
    {
        config(['app.debug' => false]);
        Route::get('/uji-error', fn () => throw new \RuntimeException('rahasia-internal'));

        $this->get('/uji-error')
            ->assertStatus(500)
            ->assertSee('Terjadi kesalahan')
            ->assertDontSee('rahasia-internal', false)
            ->assertDontSee('RuntimeException', false);
    }
}
