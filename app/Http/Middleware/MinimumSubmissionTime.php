<?php

namespace App\Http\Middleware;

use App\Models\SpamAttempt;
use Closure;
use Illuminate\Http\Request;
use Symfony\Component\HttpFoundation\Response;

class MinimumSubmissionTime
{
    public function handle(Request $request, Closure $next): Response
    {
        if ($request->isMethod('POST')) {
            $validFrom = $request->input('valid_from');

            if ($validFrom) {
                try {
                    $time = decrypt($validFrom);

                    $submittedAt = now();
                    $generatedAt = \Carbon\Carbon::parse($time);

                    if ($generatedAt->isFuture()) {
                        SpamAttempt::create([
                            'ip_address' => $request->ip(),
                            'user_agent' => $request->userAgent(),
                            'reason' => 'Form submitted before minimum 5-second time limit',
                            'route' => $request->path(),
                            'request_method' => $request->method(),
                            'attempted_at' => now(),
                        ]);

                        return redirect()
                            ->back()
                            ->with(
                                'error',
                                'Spam detected. Please wait at least 5 seconds before submitting the form.'
                            );
                    }
                } catch (\Throwable $e) {
                    // Let Spatie handle invalid/tampered honeypot timestamps.
                }
            }
        }

        return $next($request);
    }
}