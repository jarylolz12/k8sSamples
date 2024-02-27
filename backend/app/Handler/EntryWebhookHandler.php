<?php

namespace App\Handler;

use \Spatie\WebhookClient\ProcessWebhookJob;

class EntryWebhookHandler extends ProcessWebhookJob
{
    public function handle()
    {
        $data = json_decode($this->webhookCall['payload'], true);
    }
}
