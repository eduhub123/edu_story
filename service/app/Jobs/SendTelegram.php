<?php


namespace App\Jobs;

use App\Services\ServiceConnect\TelegramService;
use Illuminate\Support\Facades\Config;

class SendTelegram extends Job
{

    private $id;
    private $content;
    private $tokenBot;

    public function __construct($content, $id = null, $tokenBot = null)
    {
        if (!$id) {
            $id = Config::get('environment.TELEGRAM_CHAT_ID_ERROR_APP');
        }
        if (!$tokenBot) {
            $tokenBot = Config::get('environment.TELEGRAM_BOT_TOKEN_ERROR_APP');
        }
        $this->id       = $id;
        $this->tokenBot = $tokenBot;
        $this->content  = env('APP_NAME') . '[' . env('APP_ENV') . ']' . " _ " . $content;
    }

    public function handle(TelegramService $telegramService)
    {
        $telegramService->senToTelegram($this->id, $this->content, $this->tokenBot);
    }

}
