<?php

namespace FleetCart\Http\Middleware;

use Closure;
use Modules\User\Entities\User;
use Illuminate\Http\Request;

class UserAuth
{
    /**
     * Handle an incoming request.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \Closure  $next
     * @return mixed
     */
    public function handle( $request, Closure $next)
    {
        // dd($request->header('api_token'));
        if(! $request->header('api_token')){
            return response(['status' => false,'message' => 'api_token مطلوب لتسجيل الدخول'] ,401);
        }
        $user = User::where('api_token',$request->header('api_token'))->first();

        if(!$user){
            return response(['status' => false,'message' => 'api_token غير متطابق'] , 401);
        }
        return $next($request);
    }
}
