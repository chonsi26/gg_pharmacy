<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Symfony\Component\HttpFoundation\Response;

/**
 * Stops the browser from caching / restoring pages from the back-forward cache.
 *
 * Pages that contain forms embed a CSRF token. If the browser shows a cached
 * copy after a login/logout (or via Back/Forward), the form submits an old
 * token and Laravel answers "419 Page Expired" until the page is refreshed.
 * Sending no-store headers forces a fresh page (and fresh token) every time.
 */
class PreventBackHistory
{
    public function handle(Request $request, Closure $next): Response
    {
        $response = $next($request);

        $response->headers->set('Cache-Control', 'no-cache, no-store, max-age=0, must-revalidate, private');
        $response->headers->set('Pragma', 'no-cache');
        $response->headers->set('Expires', 'Sun, 02 Jan 1990 00:00:00 GMT');

        return $response;
    }
}
