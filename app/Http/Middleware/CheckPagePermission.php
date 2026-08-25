<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Symfony\Component\HttpFoundation\Response;

class CheckPagePermission
{
    public function handle(Request $request, Closure $next, string $page): Response
    {
        $user = $request->user();

        if (!$user) {
            return redirect()->route('login');
        }
        if ($user->company_id) {
            session(['selected_company_id' => $user->company_id]);
        }

        if (strtolower((string) $user->role) === 'admin') {
            return $next($request);
        }

        if ($user->hasPermission($page)) {
            return $next($request);
        }

        abort(403, 'You do not have access to this page.');
    }
}
