<?php

namespace Tests\Unit\Middleware;

use App\Http\Middleware\SetLocale;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\App;
use Illuminate\Support\Facades\Session;
use Tests\TestCase;

class SetLocaleTest extends TestCase
{
    public function test_it_sets_locale_from_session_when_valid(): void
    {
        Session::put('locale', 'en');
        $middleware = new SetLocale();
        $request = Request::create('/');

        $middleware->handle($request, function ($req) {
            $this->assertEquals('en', App::getLocale());
            return response('OK');
        });
    }

    public function test_it_falls_back_to_sw_for_invalid_locale(): void
    {
        Session::put('locale', 'fr');
        $middleware = new SetLocale();
        $request = Request::create('/');

        $middleware->handle($request, function ($req) {
            $this->assertEquals('sw', App::getLocale());
            return response('OK');
        });
    }
}
