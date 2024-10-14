<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Symfony\Component\HttpFoundation\Response;

class WhiteListIp
{
    public array $whitelistIps = [
        '127.0.0.1',
    ];

    public function handle(Request $request, Closure $next): Response
    {
        if (!in_array($request->ip(), $this->whitelistIps)) {
            abort(403);
        }

        return $next($request);
    }
}
