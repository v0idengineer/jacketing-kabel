<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;

class User extends Authenticatable
{
    /** @use HasFactory<\Database\Factories\UserFactory> */
    use HasFactory, Notifiable;

    /**
     * Mass assignable.
     * Tambahkan kolom baru jika nanti perlu mass-assign (opsional).
     */
    protected $fillable = [
        'name',
        'email',
        'password',
        'username',
        // 'password_updated_at', // opsional: hanya jika kamu ingin mass-assign
    ];

    /**
     * Hidden for serialization.
     */
    protected $hidden = [
        'password',
        'remember_token',
    ];

    /**
     * Casts.
     * - password: hashed (Laravel akan auto-hash saat set)
     * - password_updated_at: datetime (marker untuk force logout)
     */
    protected function casts(): array
    {
        return [
            'email_verified_at'   => 'datetime',
            'password'            => 'hashed',
            'password_updated_at' => 'datetime',
        ];
    }

    /**
     * Marker timestamp untuk deteksi perubahan password di middleware.
     * Mengembalikan UNIX timestamp (int) atau null.
     */
    public function passwordMarker(): ?int
    {
        return optional($this->password_updated_at)->timestamp;
    }

    /**
     * Helper opsional: tandai password baru saja diupdate.
     * Panggil ini setelah mengganti password.
     */
    public function markPasswordUpdated(): void
    {
        $this->password_updated_at = now();
        // tidak dipanggil save() di sini agar fleksibel dipakai dalam transaksi
    }
}
