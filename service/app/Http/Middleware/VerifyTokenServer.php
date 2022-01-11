<?php

namespace App\Http\Middleware;

use Closure;

class VerifyTokenServer
{
    /**
     * Handle an incoming request.
     *
     * @param \Illuminate\Http\Request $request
     * @param \Closure $next
     * @return mixed
     */

    public function __construct()
    {
    }

    public function handle($request, Closure $next, $guard = null)
    {
        $token = $request->header('token_server');
        if (!$token) {
            $token = $request->input('token_server');
            if (!$token) {
                $token = $request->header('token');
                if (!$token) {
                    $token = $request->input('token');
                }
            }
        }
        if (!$token || $token == 'null' || $token != env('TOKEN_TO_SERVER')) {
            $permissionDenied = [
                'message' => 'Permission denied'
            ];
            return response()->json($permissionDenied, 401);
        }

        return $next($request);
    }
}
