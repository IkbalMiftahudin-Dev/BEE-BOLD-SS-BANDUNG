<?php

namespace App\Http\Middleware;

use App\Enums\UserRole;
use Closure;
use Illuminate\Http\Request;
use Symfony\Component\HttpFoundation\Response;

class RoleMiddleware
{
    /**
     * Deny-by-default role check for authenticated users (e.g. role:admin or role:admin,editor).
     */
    public function handle(Request $request, Closure $next, string ...$roles): Response
    {
        $user = $request->user();

        if ($user === null) {
            return redirect()->guest(route('login'));
        }

        $currentRole = $user->role instanceof UserRole ? $user->role->value : (string) $user->role;
        $allowedRoles = array_map(static fn (string $role): string => strtolower(trim($role)), $roles);

        if ($allowedRoles === [] || ! in_array(strtolower($currentRole), $allowedRoles, true)) {
            abort(403, 'Anda tidak memiliki izin untuk mengakses halaman ini.');
        }

        return $next($request);
    }
}

