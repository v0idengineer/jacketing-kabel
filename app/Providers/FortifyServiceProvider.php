<?php

namespace App\Providers;

use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\ServiceProvider;
use Laravel\Fortify\Fortify;

class FortifyServiceProvider extends ServiceProvider
{
    public function register(): void {}

    public function boot(): void
    {
        // Pakai 'username' sebagai identifier utama (bukan email)
        Fortify::username('username');

        // Opsi: autentikasi manual supaya 100% pakai kolom 'username'
        Fortify::authenticateUsing(function (Request $request) {
            // Sesuaikan kolom 'username' (BUKAN 'name' / 'email')
            $user = User::where('username', $request->input('username'))->first();

            if ($user && Hash::check($request->input('password'), $user->password)) {
                return $user;
            }

            return null;
        });
    }
}
