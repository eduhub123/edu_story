<?php


namespace App\Jobs;

use App\Services\ServiceConnect\TelegramService;

class SendTelegram extends Job
{

    private $id;
    private $content;
    private $tokenBot;

    public function __construct($id, $content, $tokenBot = '')
    {
        $this->id       = $id;
        $this->content  = $content;
        $this->tokenBot = $tokenBot;
    }

    public function handle(TelegramService $telegramService)
    {
        $telegramService->senToTelegram($this->id, $this->content, $this->tokenBot);
    }

}
