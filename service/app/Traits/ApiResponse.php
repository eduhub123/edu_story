<?php

namespace App\Traits;

use Illuminate\Http\Response;

trait ApiResponse
{

    public $status  = 'fail';
    public $message = '';
    public $code    = Response::HTTP_OK;

    public function responseData($data = [], $more = [], $statusCode = Response::HTTP_OK)
    {
        $response = [
            'status'  => $this->status,
            'message' => $this->message,
            'code'    => $this->code,
            'data'    => $data
        ];
        if ($more) {
            $response = array_merge($response, $more);
        }
        return response()->json($response, $statusCode);
    }

    public function responseNewData($data = [])
    {
        $response = [
            'status'  => $this->status,
            'message' => $this->message,
            'code'    => $this->code,
            'data'    => $data
        ];
        return json_encode($response);
    }
}
