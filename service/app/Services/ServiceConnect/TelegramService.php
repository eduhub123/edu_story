<?php


namespace App\Services\ServiceConnect;


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
        $url = config('environment.API_SERVICE_TELEGRAM')  . $tokenBot . '/sendMessage?' .'chat_id='.$id.'&text='.$content;
        return Curl::to($url)->get();
    }
}
