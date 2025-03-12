<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\App;
use Illuminate\Support\Facades\Session;

class LanguageMiddleware
{
    /**
     * Handle an incoming request.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \Closure  $next
     * @return mixed
     */
    public function handle(Request $request, Closure $next)
    {
        // Session'dan dil tercihini al (yoksa config/app.php'den varsayılan dil kullanılır)
        if (Session::has('locale')) {
            $locale = Session::get('locale');
            App::setLocale($locale);
        }
        
        return $next($request);
    }
}
