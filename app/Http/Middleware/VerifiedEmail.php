<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Route;

class VerifiedEmail
{
    /**
     * Redirect unverified email users to verification.notice route 
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \Closure(\Illuminate\Http\Request): (\Illuminate\Http\Response|\Illuminate\Http\RedirectResponse)  $next
     * @return \Illuminate\Http\Response|\Illuminate\Http\RedirectResponse
     */
    public function handle(Request $request, Closure $next)
    {
        if(Auth::check()) {
            if(!Auth::user()->verified_email) {
                $currentRoute = Route::currentRouteName();
                // ignore if its verify email notification page
                if($currentRoute != 'verification.notice') {
                    // ignore routes which user can enter without email verification
                    if(in_array($currentRoute, ['logout', 'verification.verify', 'verification.resend.email', 'password.reset.show', 'password.reset.update'])) {
                        return $next($request);
                    } else {
                        return redirect()->route('verification.notice');
                    }
                }
            }
        }

        return $next($request);
    }
}
