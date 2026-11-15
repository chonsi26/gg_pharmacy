<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Symfony\Component\HttpFoundation\Response;

/**
 * Blocks the admin register page (and its form submission) until the visitor
 * has entered the one shared registration password on the gate screen.
 */
class RequireRegisterPassword
{
    public const SESSION_KEY = 'admin_register_unlocked';

    public function handle(Request $request, Closure $next): Response
    {
        if (! $request->session()->get(self::SESSION_KEY, false)) {
            return redirect()->route('admin.register.gate');
        }

        return $next($request);
    }
}
