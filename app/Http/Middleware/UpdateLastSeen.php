<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use App\Models\MagamentSystemModel\User;

class UpdateLastSeen
{
    public function handle(Request $request, Closure $next)
    {
        if (Auth::check()) {
            /** @var User|null $user */
            $user = Auth::user();

            if ($user instanceof User) {
                $user->last_seen_at = now();
                $user->save();
            }
        }

        return $next($request);
    }
}
