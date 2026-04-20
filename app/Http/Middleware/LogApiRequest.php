<?php

namespace App\Http\Middleware;

use App\Models\ApiLog;
use Closure;
use Illuminate\Http\Request;
use Symfony\Component\HttpFoundation\Response;

class LogApiRequest
{
    public function handle(Request $request, Closure $next): Response
    {
        $response = $next($request);

        // Only log authenticated requests
        if ($request->user()) {
            ApiLog::create([
                'method' => $request->method(),
                'endpoint' => $request->path(),
                'payload' => $request->except(['password', 'token']),
                'ip' => $request->ip(),
                'user_id' => $request->user()->id,
                'status_code' => $response->getStatusCode(),
            ]);
        }

        return $response;
    }
}
