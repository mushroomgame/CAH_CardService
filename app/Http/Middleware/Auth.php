<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Response;

class Auth
{
    /**
     * 验证Secret
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \Closure  $next
     * @return mixed
     */
    public function handle($request, Closure $next)
    {
        $secret = $request->input('secret');
        if ($secret != env('APP_SECRET'))
        {
            return response()->json([
                'status' => 'failed',
                'reason' => 'Not authorized.'
            ]);
        }

        return $next($request);
    }
}