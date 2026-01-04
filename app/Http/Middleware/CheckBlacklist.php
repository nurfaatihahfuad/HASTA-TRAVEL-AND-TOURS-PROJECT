<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Support\Facades\Auth;
use App\Models\Blacklist; // import model

class CheckBlacklist
{
    public function handle($request, Closure $next)
    {
        if (Auth::check()) {
            $blacklisted = Blacklist::where('customerUID', Auth::id())->exists();

            if ($blacklisted) {
                // Pastikan route 'damage.notice' wujud
                return redirect()->route('damage.notice');
            }
        }

        return $next($request);
    }
}