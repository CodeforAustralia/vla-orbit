<?php

namespace App\Http\Middleware;

use Closure;
use Carbon\Carbon;

class TwoFactor
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
        $user = auth()->user();

        if(auth()->check() && $user->two_factor_code)
        {
            if($user->two_factor_expires_at->lt(Carbon::now()))
            {
                $user->resetTwoFactorCode();
                auth()->logout();

                // return redirect()->route('login')
                //     ->withMessage('The two factor code has expired. Please login again.');
                return redirect('/verify')
                    ->withErrors(['two_factor_code' => 'The two factor code has expired. Please login again.']);

            }

            if(!$request->is('verify*'))
            {
                return redirect()->route('verify.index');
            }
        }

        return $next($request);
    }
}
