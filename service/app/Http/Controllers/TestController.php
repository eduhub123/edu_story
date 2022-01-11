<?php


namespace App\Http\Controllers;


use Illuminate\Http\Request;

class TestController extends Controller
{

    private $request;

    public function __construct(
        Request $request
    ) {
        $this->request = $request;
    }

    public function demo()
    {
        $this->status  = 'success';
        $this->message = __('app.success');
        return $this->responseData([]);
    }
}
