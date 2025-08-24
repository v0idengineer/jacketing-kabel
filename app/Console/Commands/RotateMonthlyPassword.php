<?php

namespace App\Console\Commands;

use App\Mail\AllUsersMonthlyPasswordsMail;
use App\Models\User;
use Illuminate\Console\Command;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Mail;
use Illuminate\Support\Facades\Schema;
use Illuminate\Support\Str;

class RotateMonthlyPassword extends Command
{
    protected $signature = 'user:rotate-password';
    protected $description = 'Rotate password semua user dan kirim rekap ke 1 email';

    public function handle(): int
    {
        $recipient = '7um4t4n@gmail.com'; // tujuan rekap

        $users = User::query()->get();
        if ($users->isEmpty()) {
            $this->warn('Tidak ada user.');
            return self::SUCCESS;
        }

        $accounts = [];

        foreach ($users as $user) {
            $plain = method_exists(Str::class, 'password') ? Str::password(12) : Str::random(16);

            $user->password = Hash::make($plain);
            // penanda untuk middleware force-logout kalau dipakai
            if (method_exists($user, 'markPasswordUpdated')) {
                $user->markPasswordUpdated();
            } else {
                $user->password_updated_at = now();
            }
            // jadwal rotasi berikutnya (opsional)
            if ($user->isFillable('next_password_rotate_at') || Schema::hasColumn('users', 'next_password_rotate_at')) {
                $user->next_password_rotate_at = now()->startOfMonth()->addMonth()->setTime(0, 5);
            }
            $user->save();

            // paksa logout (kalau session driver = database)
            try {
                if (config('session.driver') === 'database') {
                    DB::table(config('session.table', 'sessions'))
                        ->where('user_id', $user->id)->delete();
                }
            } catch (\Throwable $e) {
            }

            $accounts[] = [
                'username' => $user->username ?? $user->name,
                'email'    => $user->email,
                'password' => $plain,
            ];
        }

        Mail::to($recipient)->send(new AllUsersMonthlyPasswordsMail($accounts));

        $this->info('Rotasi selesai. Rekap terkirim ke ' . $recipient);
        return self::SUCCESS;
    }
}
