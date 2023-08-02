<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;
use App;
use Illuminate\Support\Str;
class Locale
{
    /**
     * Handle an incoming request.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \Closure(\Illuminate\Http\Request): (\Illuminate\Http\Response|\Illuminate\Http\RedirectResponse)  $next
     * @return \Illuminate\Http\Response|\Illuminate\Http\RedirectResponse
     */
    
    public function handle(Request $request, Closure $next)
    {
        //不需跳轉頁的route
        $without_route = ['mgr', 'util', 'linebot'];
        //做語系跳轉用
        if (env('LOCALE_ENABLE') && $request->isMethod('get')) {
            $segment = $request->segment(1)??config('app.locale');
            $segments = $request->segments();
            
            if(Route::getRoutes()->match($request) && !in_array($segment, config('app.locales')) && !in_array($segments[0], $without_route)){
                if ($segments[0] == 'locale') {
                    $selected_locale = $segments[1];
                    app()->setLocale($selected_locale);

                    $prev = str_replace(env('APP_URL'), '', url()->previous());
                    $redirect_url = env('APP_URL')."/".$selected_locale;
                    if (Str::contains(substr($prev, 1, 2), config('app.locales'))) {
                        $redirect_url .= substr($prev, 3, strlen($prev) - 1);
                    }
                    return redirect()->to($redirect_url);
                }
                array_unshift($segments, config('app.fallback_locale'));
                
                return redirect()->to(implode('/', $segments));
            }

            if (in_array($segment, config('app.locales'))) {
                app()->setLocale($segment);
            }
        }
        
        return $next($request);
    }

    public static function prefix(string $segment)
    {
        if (env('LOCALE_ENABLE')) {
            if (false === empty($segment) && true === in_array($segment, config('app.locales'))) {
                app()->setLocale($segment);
                return $segment;
            }
            app()->setLocale(config('app.locale'));
        }
        return '';
    }
}
