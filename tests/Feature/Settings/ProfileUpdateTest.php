<?php

namespace Tests\Feature\Settings;

use App\Models\Pengguna;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class ProfileUpdateTest extends TestCase
{
    use RefreshDatabase;

    public function test_profile_page_is_displayed()
    {
        $user = Pengguna::factory()->create();

        $this->actingAs($user)->get(route('profile.edit'))->assertOk();
    }

    public function test_email_can_be_updated_and_requires_new_verification()
    {
        $user = Pengguna::factory()->create();

        $this->actingAs($user)
            ->patch(route('profile.update'), ['email' => 'baru@example.com'])
            ->assertSessionHasNoErrors()
            ->assertRedirect(route('profile.edit'));

        $user->refresh();

        $this->assertSame('baru@example.com', $user->email);
        $this->assertNull($user->email_verified_at);
    }

    public function test_email_verification_status_is_unchanged_when_the_email_address_is_unchanged()
    {
        $user = Pengguna::factory()->create();

        $this->actingAs($user)
            ->patch(route('profile.update'), ['email' => $user->email])
            ->assertSessionHasNoErrors()
            ->assertRedirect(route('profile.edit'));

        $this->assertNotNull($user->refresh()->email_verified_at);
    }

    public function test_primary_key_is_id_pengguna_on_table_pengguna()
    {
        $user = Pengguna::factory()->create();

        $this->assertSame('pengguna', $user->getTable());
        $this->assertSame('id_pengguna', $user->getKeyName());
        $this->assertDatabaseHas('pengguna', ['id_pengguna' => $user->id_pengguna]);
    }
}
