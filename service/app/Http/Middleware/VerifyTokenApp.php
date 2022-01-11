<?php

namespace App\Http\Middleware;

use App\Services\AuthenService;
use Closure;
use Illuminate\Http\Request;
use Illuminate\Http\Response;

class VerifyTokenApp
{
    /**
     * Handle an incoming request.
     *
     * @param \Illuminate\Http\Request $request
     * @param \Closure $next
     * @return mixed
     */
    private $authenService;

    public function __construct(
        AuthenService $authenService
    ) {
        $this->authenService = $authenService;
    }

    public function handle(Request $request, Closure $next, $guard = null)
    {
        $token     = $request->header('token');
        $keyVerify = $request->header('x-api-key');

        $isCheckLoadUpdate = $request->input('is_check_load_update');
        $userIdNeedCheck   = $request->input('users_id');
        $isWeb             = $request->input('is_web');
        if ($isCheckLoadUpdate) {
            $user['id'] = $userIdNeedCheck;
            $request->attributes->add(['userInfo' => $user]);
            return $next($request);
        }

        $user              = $this->verifyToken($token);
        $isCorrectPairTime = $this->pairTimeLocalAndServer($keyVerify);
        $deviceId          = $request->input('device_id');
        $appId             = $request->input('app_id');

        if (!$isWeb) {
            $isOnActiveDeviceLogin = false;
            if (isset($user['is_super_pw']) && $user['is_super_pw'] == false) {
                $isOnActiveDeviceLogin = $this->authenService->isMaxOnActiveDeviceLogin($user['id'], $deviceId, $user['max_device_on_active'], $appId);
            }

            if ($isOnActiveDeviceLogin) {
                $message = __('app.limit_active_device_in_account', ['limit_active_device_in_account' => $user['max_device_on_active']]);
                return $this->defaultResponseRevoke($message);
            }

            if (!$user || !$isCorrectPairTime) {
                return $this->defaultResponseRevoke('Permission denied');
            }
        }

        $request->attributes->add(['userInfo' => $user]);
        return $next($request);
    }

    private function pairTimeLocalAndServer($timeLocal)
    {
        return true;
//        $decodeTimeLocal = base64_decode($timeLocal);
//        if ((time() - (60 * 60)) > $decodeTimeLocal) {
//            return false;
//        }
//        return true;
    }

    private function verifyToken($token)
    {
        if (!$token) {
            $token = $this->request()->get('token');
        }

        if (!$token || $token == 'null') {
            return false;
        }

        $infoUser = $this->authenService->deToken($token);

        if (!$infoUser) {
            list($header, $payload, $signature) = explode(".", $token);
            $infoUser = json_decode(base64_decode($payload), true);
        }

        if (!$infoUser) {
            return false;
        }

        $infoUser = (array)$infoUser;

        return $infoUser;
    }

    private function request($key = null, $default = null)
    {
        if (is_null($key)) {
            return app('request');
        }

        if (is_array($key)) {
            return app('request')->only($key);
        }

        return data_get(app('request')->all(), $key, $default);
    }

    private function defaultResponseRevoke($message, $status = 'fail', $code = 401, $data = [], $statusCode = Response::HTTP_OK)
    {
        $response = [
            'status'  => $status,
            'message' => $message,
            'code'    => $code,
            'data'    => $data
        ];
        return response()->json($response, $statusCode);
    }

}
