<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Symfony\Component\HttpFoundation\Response;

class CheckAppToken
{
    /**
     * Handle an incoming request.
     *
     * @param  \Closure(\Illuminate\Http\Request): (\Symfony\Component\HttpFoundation\Response)  $next
     */
    public function handle($request, Closure $next)
    {
        $appToken = $request->header('X-App-Token'); // nebo jakkoli
        if (!$appToken) {
            return response()->json(['error' => 'Missing app token'], 401);
        }

        // najdu v DB
        $app = \App\Models\App::where('token', $appToken)->first();
        if (!$app) {
            return response()->json(['error' => 'Invalid app token'], 403);
        }

        // Můžu si nastavit do requestu $request->attributes->set('app', $app);
        return $next($request);
    }
}
