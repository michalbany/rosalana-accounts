<?php

namespace App\Http\Middleware;

use App\Models\Traits\ApiResponses;
use Closure;
use Illuminate\Http\Request;
use Symfony\Component\HttpFoundation\Response;

class CheckAppToken
{
    use ApiResponses;

    /**
     * Handle an incoming request.
     *
     * @param  \Closure(\Illuminate\Http\Request): (\Symfony\Component\HttpFoundation\Response)  $next
     */
    public function handle($request, Closure $next)
    {
        $appToken = $request->header('X-App-Token'); // nebo jakkoli
        if (!$appToken) {
            return $this->unauthorized(new \Exception('Missing app token'));
        }

        // najdu v DB
        $app = \App\Models\App::where('token', $appToken)->first();
        if (!$app) {
            return $this->forbidden(new \Exception('Invalid app token'));
        }
        return $next($request);
    }
}
