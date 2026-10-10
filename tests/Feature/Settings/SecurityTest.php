<?php

namespace Tests\Feature\Settings;

use App\Models\Pengguna;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Route;
use Inertia\Testing\AssertableInertia as Assert;
use Tests\TestCase;

class SecurityTest extends TestCase
{
    use RefreshDatabase;

    public function test_security_page_is_displayed()
    {
        $user = Pengguna::factory()->create();

        $this->actingAs($user)
            ->withSession(['auth.password_confirmed_at' => time()])
            ->get(route('security.edit'))
            ->assertInertia(fn (Assert $page) => $page
                ->component('settings/security')
                ->has('passwordRules')
                ->missing('canManageTwoFactor')
                ->missing('canManagePasskeys'),
            );
    }

    public function test_security_page_requires_password_confirmation()
    {
        $user = Pengguna::factory()->create();

        $this->actingAs($user)
            ->get(route('security.edit'))
            ->assertRedirect(route('password.confirm'));
    }

    public function test_password_can_be_updated()
    {
        $user = Pengguna::factory()->create();

        $response = $this
            ->actingAs($user)
            ->from(route('security.edit'))
            ->put(route('user-password.update'), [
                'current_password' => 'password',
                'password' => 'kata-sandi-baru-12',
                'password_confirmation' => 'kata-sandi-baru-12',
            ]);

        $response
            ->assertSessionHasNoErrors()
            ->assertRedirect(route('security.edit'));

        $this->assertTrue(Hash::check('kata-sandi-baru-12', $user->refresh()->kata_sandi));
    }

    public function test_password_shorter_than_12_characters_is_rejected()
    {
        $user = Pengguna::factory()->create();

        $this->actingAs($user)
            ->from(route('security.edit'))
            ->put(route('user-password.update'), [
                'current_password' => 'password',
                'password' => 'pendek-123',
                'password_confirmation' => 'pendek-123',
            ])
            ->assertSessionHasErrors('password');
    }

    public function test_correct_password_must_be_provided_to_update_password()
    {
        $user = Pengguna::factory()->create();

        $response = $this
            ->actingAs($user)
            ->from(route('security.edit'))
            ->put(route('user-password.update'), [
                'current_password' => 'wrong-password',
                'password' => 'kata-sandi-baru-12',
                'password_confirmation' => 'kata-sandi-baru-12',
            ]);

        $response
            ->assertSessionHasErrors('current_password')
            ->assertRedirect(route('security.edit'));
    }

    public function test_mfa_passkey_registration_and_account_deletion_routes_do_not_exist()
    {
        foreach (['two-factor.login', 'two-factor.enable', 'passkeys.login', 'register', 'profile.destroy'] as $name) {
            $this->assertFalse(Route::has($name), "Route {$name} seharusnya tidak ada (DEC-041, DEC-071)");
        }
    }
}
