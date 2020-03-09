<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Support\Facades\Redirect;
use Illuminate\Contracts\Auth\Guard;

class AuthDomainAdmin
{
    /**
     * The Guard implementation.
     *
     * @var Guard
     */
    protected $auth;

    /**
     * Create a new filter instance.
     *
     * @param  Guard  $auth
     * @return void
     */
    public function __construct(Guard $auth)
    {
        $this->auth = $auth;
    }

    /**
     * Handle an incoming request.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \Closure  $next
     * @return mixed
     */
    public function handle($request, Closure $next)
    {
        $BASE_DOMAIN = 'https://www.hostel-granada.es';
        
        $origin = $request->header('origin');
        $referer = $request->header('referer');
        
        if($BASE_DOMAIN == $origin){
            return $next($request);
        }

        return Redirect::to($BASE_DOMAIN);
    }
}
