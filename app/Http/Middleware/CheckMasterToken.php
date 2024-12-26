<?php

namespace App\Http\Middleware;

use App\Models\Traits\ApiResponses;
use Closure;
use Illuminate\Http\Request;
use Symfony\Component\HttpFoundation\Response;

class CheckMasterToken
{
    use ApiResponses;

    /**
     * Handle an incoming request.
     *
     * @param  \Closure(\Illuminate\Http\Request): (\Symfony\Component\HttpFoundation\Response)  $next
     */
    public function handle(Request $request, Closure $next): Response
    {
        $appToken = $request->header('X-App-Token');
        if (!$appToken) {
            return $this->unauthorized(new \Exception('Missing app token'));
        }

        $masterToken = env('ROSALANA_MASTER_TOKEN', 'master-token');

        if ($appToken !== $masterToken) {
            return $this->unauthorized(new \Exception('App is not authorized for this action'));
        }
        
        return $next($request);
    }
}
