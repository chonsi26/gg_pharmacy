<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Illuminate\Support\Str;
use Symfony\Component\HttpFoundation\Response;

/**
 * Gives admin and staff their own session cookies, separate from the customer site.
 *
 * Rotating a session during login on one area must not invalidate forms or
 * authentication sessions in another area.
 *
 * MUST be registered as a GLOBAL middleware (app/Http/Kernel.php -> $middleware)
 * so it runs BEFORE the "web" group's StartSession middleware.
 */
class SeparateRoleSessions
{
    public function handle(Request $request, Closure $next): Response
    {
        $suffix = match (true) {
            $request->is('admin', 'admin/*') => '_admin',
            $request->is('staff', 'staff/*') => '_staff',
            default => null,
        };

        if ($suffix === null) {
            return $next($request);
        }

        $base = (string) config('session.cookie');
        $cookie = Str::endsWith($base, $suffix) ? $base : $base . $suffix;

        config(['session.cookie' => $cookie]);
        app('session')->forgetDrivers();

        try {
            return $next($request);
        } finally {
            app('session')->forgetDrivers();
            config(['session.cookie' => $base]);
        }
    }
}
