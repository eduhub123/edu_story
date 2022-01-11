<?php

namespace App\Http\Middleware;

use Closure;
use Exception;
use Firebase\JWT\ExpiredException;
use Illuminate\Http\Request;
use Firebase\JWT\JWT;
use App\Services\RedisService;

class UserAuthentication
{
    private $redisService;
    public function __construct(
        RedisService $redisService
    )
    {
        $this->redisService = $redisService;
    }

    public function handle($request, Closure $next, $guard = null)
    {
        $user = $this->verifyToken($request);
        if (!$user) {
            $permissionDenied = [
                'message' => 'Permission denied'
            ];
            return response()->json($permissionDenied, 401);
        }
        $request->attributes->add(['user' => $user]);
        $request->auth = $user;
        return $next($request);
    }

    public function verifyToken(Request $request)
    {
        $token = $request->header('token');
        if (!$token) {
            $token = $request->input('token');
        }

        if ($token === env('TOKEN_TO_SERVER')) {
            return true;
        }

        if (!$token || $token == 'null') {
            return false;
        }
        try {
            $decode_token = JWT::decode($token, env('KEY_TOKEN'), ['HS256']);
        } catch (ExpiredException | Exception $e) {
            return false;
        }
        $user = (array)$decode_token;

        if ($this->hasExpiredToken($user)) {
            return false;
        }

        return $user;
    }

    private function hasExpiredToken($user)
    {
        $keyToken = "USER_REGISTER_TIME_EXPIRED" . $user['id'];

        if (!$this->redisService->get($keyToken)) {
            $this->redisService->set($keyToken, ['active' => true], $user['time_during_system'] * 60);
            return true;
        }

        $this->redisService->set($keyToken, "true", $user['time_during_system'] * 60);
        return true;
    }

}
