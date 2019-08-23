<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Response;

class Type
{
    /**
     * 验证Type
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \Closure  $next
     * @return mixed
     */
    public function handle($request, Closure $next)
    {
        if (!in_array($request->type, ['blackcards', 'whitecards']))
        {
            return response()->json([
                'status' => 'failed',
                'reason' => "'$request->type' is not a valid type"
            ]);
        }
        
        return $next($request);
    }
}