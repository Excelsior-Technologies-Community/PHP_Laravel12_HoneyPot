<?php

namespace App\Spam;

use Closure;
use Illuminate\Http\Request;
use Spatie\Honeypot\SpamResponder\SpamResponder;

class CustomSpamResponder implements SpamResponder
{
    public function respond(Request $request, Closure $next)
    {
        return redirect()
            ->back()
            ->with('error', 'Spam detected. Please try again slowly.');
    }
}
