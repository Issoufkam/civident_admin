<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use App\Enums\UserRole;
use Illuminate\Support\Facades\Auth;
use Symfony\Component\HttpFoundation\Response;

class CheckRole
{
    // app/Http/Middleware/CheckRole.php
public function handle(Request $request, Closure $next, $role)
{
    if (!auth()->check()) {
        return redirect()->route('login');
    }

    $userRole = auth()->user()->role;

    if ($userRole !== $role) {
        abort(403, 'Accès non autorisé');
    }

    return $next($request);
}
}
