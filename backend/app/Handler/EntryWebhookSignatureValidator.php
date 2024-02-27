<?php

namespace App\Handler;

use Spatie\WebhookClient\SignatureValidator\SignatureValidator;
use Illuminate\Http\Request;
use Spatie\WebhookClient\WebhookConfig;

class EntryWebhookSignatureValidator implements SignatureValidator
{
    public function isValid(Request $request, WebhookConfig $config): bool
    {
        // $computedSignature = hash_hmac('sha256', $request->getContent(), "shifl_test_webhook");
        return true;
    }
}
