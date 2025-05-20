<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Symfony\Component\HttpFoundation\Response;
use App\Enums\UserRole;
use App\Models\User;

class CheckRole
{
    /**
     * Handle an incoming request.
     *
     * @param  \Closure(\Illuminate\Http\Request): (\Symfony\Component\HttpFoundation\Response)  $next
     */
    // app/Http/Middleware/CheckRole.php
public function handle($request, Closure $next, $role)
    {
        $allowedRoles = [
            'admin' => UserRole::ADMIN,
            'agent' => UserRole::AGENT,
        ];


        if (!auth()->check() || !array_key_exists($role, $allowedRoles)) {
            return redirect()->route('login');
        }

        if (auth()->user()->role !== $allowedRoles[$role]) {
            abort(403, 'Accès non autorisé');
        }

        return $next($request);
    }
}
