<?php

namespace App\Http\Middleware;

use Closure;

class Cors
{
    /**
     * Handle an incoming request.
     *
     * Please add header('Access-Control-Allow-Origin: http://example.com');
     * & header('Access-Control-Allow-Credentials: true');
     * & header('Access-Control-Allow-Headers', 'accept, content-type,x-xsrf-token, x-csrf-token, token');
     * & header('Access-Control-Allow-Methods', 'POST, GET, OPTIONS, PUT, DELETE')
     * at the top of your route file.
     *
     * @param \Illuminate\Http\Request $request
     * @param \Closure $next
     * @return mixed
     */

    public function handle($request, Closure $next)
    {
        $IlluminateResponse = 'Illuminate\Http\Response';
        $SymfonyResponse    = 'Symfony\Component\HttpFoundation\Response';
        $headers            =
            [
                'Access-Control-Allow-Origin'      => '*',
                'Access-Control-Allow-Methods'     => 'POST, GET, OPTIONS, PUT, DELETE',
                'Access-Control-Allow-Credentials' => 'true',
                'Access-Control-Max-Age'           => '86400',
                'Access-Control-Allow-Headers'     => 'Content-Type, Authorization, X-Requested-With, token, , Language, Country'
            ];

        if ($request->isMethod('OPTIONS')) {
            return response()->json('{"method":"OPTIONS"}', 200, $headers);
        }
        $response = $next($request);
        if ($response instanceof $IlluminateResponse) {
            foreach ($headers as $key => $value) {
                $response->header($key, $value);
            }
            return $response;
        }

        if ($response instanceof $SymfonyResponse) {
            foreach ($headers as $key => $value) {
                $response->headers->set($key, $value);
            }
            return $response;
        }

        return $response;
    }
}
