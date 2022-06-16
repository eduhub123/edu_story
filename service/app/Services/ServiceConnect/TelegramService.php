<?php


namespace App\Services\ServiceConnect;


use Illuminate\Support\Facades\Config;
use Ixudra\Curl\Facades\Curl;

class TelegramService
{
    public function __construct()
    {
    }

    public static function senToTelegram($id, $content, $tokenBot = '')
    {
        if(!$tokenBot) {
            $tokenBot = env('TELEGRAM_BOT_TOKEN');
        }
        $url = Config::get('environment.API_SERVICE_TELEGRAM')  . $tokenBot . '/sendMessage?' .'chat_id='.$id.'&text='.$content;
        return Curl::to($url)->get();
    }
}
