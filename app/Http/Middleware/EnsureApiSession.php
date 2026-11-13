<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Session;

/** Rute operasional gudang wajib memiliki token sesi hasil login. */
class EnsureApiSession
{
    public function handle(Request $request, Closure $next)
    {
        if (! Session::has('api_token')) {
            return redirect()->route('login');
        }

        return $next($request);
    }
}
