<?php


namespace App\Jobs;

use App\Services\ServiceConnect\TelegramService;

class SendTelegram extends Job
{

    private $id;
    private $content;
    private $tokenBot;

    public function __construct($content, $id = null, $tokenBot = null)
    {
        if (!$id) {
            $id = config('environment.TELEGRAM_CHAT_ID_ERROR_APP');
        }
        if (!$tokenBot) {
            $tokenBot = config('environment.TELEGRAM_BOT_TOKEN_ERROR_APP');
        }
        $this->id       = $id;
        $this->tokenBot = $tokenBot;
        $this->content  = $content;
    }

    public function handle(TelegramService $telegramService)
    {
        $telegramService->senToTelegram($this->id, $this->content, $this->tokenBot);
    }

}
