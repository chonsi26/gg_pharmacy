<?php

namespace Tests\Feature;

use Illuminate\Support\Facades\Route;
use Tests\TestCase;

class SeparateAuthSessionsTest extends TestCase
{
    public function test_customer_admin_and_staff_use_independent_session_cookies(): void
    {
        Route::middleware('web')->get('/admin/session-cookie-test', fn () => response('ok'));
        Route::middleware('web')->get('/staff/session-cookie-test', fn () => response('ok'));
        Route::middleware('web')->get('/customer/session-cookie-test', fn () => response('ok'));

        $baseCookie = config('session.cookie');

        $this->get('/admin/session-cookie-test')
            ->assertCookie($baseCookie . '_admin');

        $this->get('/customer/session-cookie-test')
            ->assertCookie($baseCookie);

        $this->get('/staff/session-cookie-test')
            ->assertCookie($baseCookie . '_staff');

        $this->get('/admin/session-cookie-test')
            ->assertCookie($baseCookie . '_admin');

        $this->assertSame($baseCookie, config('session.cookie'));
    }
}
