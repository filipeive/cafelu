<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Symfony\Component\HttpFoundation\Response;

class SetLocale
{
    /**
     * Handle an incoming request.
     *
     * @param  \Closure(\Illuminate\Http\Request): (\Symfony\Component\HttpFoundation\Response)  $next
     */
    public function handle(Request $request, Closure $next): Response
    {
        // Set Locale
        $locale = session('locale', \App\Models\Setting::get('system_language', config('app.locale')));
        app()->setLocale($locale);

        // Set Timezone
        $timezone = \App\Models\Setting::get('system_timezone', config('app.timezone'));
        date_default_timezone_set($timezone);
        config(['app.timezone' => $timezone]);

        return $next($request);
    }
}
