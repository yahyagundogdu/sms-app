<?php

namespace App\Services;

use Illuminate\Support\Facades\Http;
use Illuminate\Support\Facades\Log;

class SmsSenderService
{
    protected string $apiUrl;
    protected mixed $apiId;
    protected mixed $apiKey;

    public function __construct()
    {
        $this->apiUrl = config('smsconfig.sms_api_url');

        $this->apiId = config('smsconfig.sms_api_id');
        $this->apiKey = config('smsconfig.sms_api_key');
    }

    public function sendNotification(string $title, string $message, string $userId, array $data = []): bool
    {
        sleep(1);
        return true;
    }
}
