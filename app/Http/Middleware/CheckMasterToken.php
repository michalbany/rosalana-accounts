<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Symfony\Component\HttpFoundation\Response;

class CheckMasterToken
{
    /**
     * Handle an incoming request.
     *
     * @param  \Closure(\Illuminate\Http\Request): (\Symfony\Component\HttpFoundation\Response)  $next
     */
    public function handle(Request $request, Closure $next): Response
    {
        $appToken = $request->header('X-App-Token');
        if (!$appToken) {
            return response()->json(['error' => 'Missing app token'], 401);
        }

        $masterToken = env('ROSALANA_MASTER_TOKEN', 'master-token');

        if ($appToken !== $masterToken) {
            return response()->json(['error' => 'App token is not master token'], 403);
        }
        
        return $next($request);
    }
}
