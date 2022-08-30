<?php

namespace FleetCart\Http\Middleware;

use Closure;
use App\User;

class AuthKeyHeaders
{
    /**
     * Handle an incoming request.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \Closure  $next
     * @return mixed
     */
    public function handle($request, Closure $next)
    {
        if(! $request->header('Authorization')){
            return response(['status' => false,'message' => 'APP_KEY IS REQUIRED'],401);
        }

        if($request->header('Authorization') != env('PHONE_APP_KEY')){
            return response(['status' => false,'message' => 'APP_KEY INCORRECT'],401);
        }
        
        return $next($request);
    }
}
