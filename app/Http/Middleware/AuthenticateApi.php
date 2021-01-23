<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;

class AuthenticateApi
{
    /**
     * Handle an incoming request.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \Closure  $next
     * @return mixed
     */
    public function handle(Request $request, Closure $next)
    {
        $apiKey = $request->header('API_KEY');
       
        // return if not authorized
        if (!$apiKey || $apiKey !== config('app.api_key')) {
            return response()->json([
                'message' => 'Invalid Authentication'
            ],403);
        }

        return $next($request);
    }
}
