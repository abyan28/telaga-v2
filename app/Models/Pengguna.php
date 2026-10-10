<?php

namespace App\Models;

use Database\Factories\PenggunaFactory;
use Illuminate\Contracts\Auth\MustVerifyEmail;
use Illuminate\Database\Eloquent\Attributes\Fillable;
use Illuminate\Database\Eloquent\Attributes\Hidden;
use Illuminate\Database\Eloquent\Casts\Attribute;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;
use Illuminate\Support\Carbon;
use Illuminate\Support\Facades\Hash;

/**
 * Akun login (DEC-028). Nama lengkap akan berada di tabel `orang` (BR-ID-002, Tahap 1);
 * sampai relasi itu ada, `name` memakai email agar UI bawaan tetap berfungsi.
 *
 * @property int $id_pengguna
 * @property string $email
 * @property string $kata_sandi
 * @property string $status_akun
 * @property Carbon|null $email_verified_at
 * @property Carbon|null $terakhir_login_at
 * @property string|null $remember_token
 * @property Carbon|null $created_at
 * @property Carbon|null $updated_at
 */
#[Fillable(['email', 'password'])]
#[Hidden(['kata_sandi', 'remember_token'])]
class Pengguna extends Authenticatable implements MustVerifyEmail
{
    /** @use HasFactory<PenggunaFactory> */
    use HasFactory, Notifiable;

    protected $table = 'pengguna';

    protected $primaryKey = 'id_pengguna';

    protected $appends = ['name'];

    /**
     * Kolom kata sandi di database bernama `kata_sandi`; Laravel dan Fortify tetap memakai `password`.
     */
    public function getAuthPasswordName(): string
    {
        return 'kata_sandi';
    }

    /**
     * @return Attribute<string|null, string>
     */
    protected function password(): Attribute
    {
        return Attribute::make(
            get: fn () => $this->attributes['kata_sandi'] ?? null,
            set: fn (string $value) => ['kata_sandi' => Hash::isHashed($value) ? $value : Hash::make($value)],
        );
    }

    /**
     * @return Attribute<string, never>
     */
    protected function name(): Attribute
    {
        return Attribute::make(get: fn () => $this->email);
    }

    /**
     * @return array<string, string>
     */
    protected function casts(): array
    {
        return [
            'email_verified_at' => 'datetime',
            'terakhir_login_at' => 'datetime',
            'kata_sandi' => 'hashed',
        ];
    }
}
