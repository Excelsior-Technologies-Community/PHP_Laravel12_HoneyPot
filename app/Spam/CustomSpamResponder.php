<?php

namespace App\Spam;

use App\Models\SpamAttempt;
use Closure;
use Illuminate\Http\Request;
use Spatie\Honeypot\SpamResponder\SpamResponder;

class CustomSpamResponder implements SpamResponder
{
    public function respond(Request $request, Closure $next)
    {
        SpamAttempt::create([
            'ip_address' => $request->ip(),
            'user_agent' => $request->userAgent(),
            'reason' => 'Honeypot spam protection triggered',
            'route' => $request->path(),
            'request_method' => $request->method(),
            'attempted_at' => now(),
        ]);

        app(\App\Services\SpamSecurityService::class)->evaluateAutoBlacklistPolicy($request->ip());

        return redirect()
            ->back()
            ->with('error', 'Spam detected. Your submission was blocked.');
    }
}