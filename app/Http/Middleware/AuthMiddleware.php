<?php

namespace App\Http\Middleware;

use Closure;

class AuthMiddleware
{
    /**
     * Handle an incoming request.
     *
     * @param  \Illuminate\Http\Request $request
     * @param  \Closure $next
     * @return mixed
     */
    public function handle($request, Closure $next)
    {
        $ip = $request->ip();
        $canCheck = false;
        $value = json_decode(app('cache')->get($ip), true);
        if ($value != null) {
            if (($value['time'] + 300) < time()) {
                $canCheck = true;
            } else {
                if ($value['cnt'] >= 3) {
                    abort(500);
                } else {
                    $canCheck = true;
                }
            }
        } else {
            $canCheck = true;
            $value = ['time' => time(), 'cnt' => 0];
        }
        if ($canCheck) {
            if ((string)($request->route()[2]['AccessKey']) != env('ACCESS_KEY')) {
                $value['cnt']++;
                app('cache')->put($ip, json_encode($value), 600);
                return redirect()->route('auth');
            } else {
                app('cache')->forget($ip);
            }
        } else {
            return redirect()->route('auth');
        }
        return $next($request);
    }
}
