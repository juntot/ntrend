<?php

namespace App\Http\Middleware;

use Closure;
use session;

class AuthUsers
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
        if($request->session()->get('auth.empID'))
        //  && $request->session()->get('auth.type') == 0 ||
        //    $request->session()->get('auth.type') && $request->session()->get('auth.type') == 1)
        {

            $response = $next($request);
            return $response->header('Cache-Control', 'nocache, no-store, max-age=0, must-revalidate')
            ->header('Pragma', 'no-cache')
            ->header('Expires', 'Fri, 01 Jan 1990 00:00:00 GMT');
        }
        return redirect('/login');
    }

}
