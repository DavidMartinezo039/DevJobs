<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Symfony\Component\HttpFoundation\Response;

class CheckGodOrModerator
{
    public function handle(Request $request, Closure $next): Response
    {
        if(!$request->user()->hasRole('god') && !$request->user()->hasRole('moderator')) {
            if ($request->expectsJson()) {
                abort(403, __('You are not authorized to view this page.'));
            }
            return redirect()->route('home');
        };

        return $next($request);
    }
}
