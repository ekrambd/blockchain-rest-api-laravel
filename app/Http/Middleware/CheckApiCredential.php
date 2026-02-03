<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Symfony\Component\HttpFoundation\Response;

class CheckApiCredential
{
    /**
     * Handle an incoming request.
     *
     * @param  \Closure(\Illuminate\Http\Request): (\Symfony\Component\HttpFoundation\Response)  $next
     */
    public function handle(Request $request, Closure $next): Response
    {
        $apiKey = $request->header('api_key');
        $secret = $request->header('secret');

        if(
            $apiKey !== config('services.api.api_key') ||
            $secret !== config('services.api.secret')
        ){
            return response()->json([
                'status' => false,
                'message' => 'Invalid API Key Or Secret Key'
            ], 400);
        }

        return $next($request);
    }
}
