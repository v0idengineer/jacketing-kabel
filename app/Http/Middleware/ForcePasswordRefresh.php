<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;

class ForcePasswordRefresh
{
    public function handle(Request $request, Closure $next)
    {
        if (auth()->check()) {
            $user   = auth()->user();
            $dbTs   = optional($user->password_updated_at)->timestamp;
            $sessTs = $request->session()->get('password_marker');

            if (!$sessTs) {
                $request->session()->put('password_marker', $dbTs);
            } elseif ($dbTs && $dbTs > $sessTs) {
                auth()->logout();
                $request->session()->invalidate();
                $request->session()->regenerateToken();

                return redirect()
                    ->route('login')
                    ->withErrors(['email' => 'Password akun telah diperbarui. Silakan login ulang.']);
            }
        }
        return $next($request);
    }
}
