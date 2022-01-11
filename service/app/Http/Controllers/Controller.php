<?php

namespace App\Http\Controllers;

use App\Traits\ApiResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;
use Laravel\Lumen\Routing\Controller as BaseController;

class Controller extends BaseController
{
    use ApiResponse;

    protected function validateBase(Request $request, $rules)
    {
        $isShowValidate = $request->input('is_show_validate', true);
        $validator      = Validator::make($request->all(), $rules);

        if ($validator->fails()) {
            if ($isShowValidate) {
                $this->message = $validator->errors();
            } else {
                $this->message = __('app.invalid_params');
            }
            return true;
        }
        return false;
    }

}
