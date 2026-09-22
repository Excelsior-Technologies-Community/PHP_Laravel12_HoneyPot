<?php

namespace App\Http\Middleware;

use App\Models\BlockedIp;
use App\Models\SpamAttempt;
use Closure;
use Illuminate\Http\Request;
use Symfony\Component\HttpFoundation\Response;

class BlockedIpMiddleware
{
    public function handle(Request $request, Closure $next): Response
    {
        $ip = $request->ip();

        $blocked = BlockedIp::where('ip_address', $ip)->exists();

        if ($blocked) {
            SpamAttempt::create([
                'ip_address' => $ip,
                'user_agent' => $request->userAgent(),
                'reason' => 'Request rejected because IP address is blocked',
                'route' => $request->path(),
                'request_method' => $request->method(),
                'attempted_at' => now(),
            ]);

            return redirect()
                ->back()
                ->with('error', 'Your IP address has been blocked from submitting this form.');
        }

        return $next($request);
    }
}