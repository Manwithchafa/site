<?php

namespace App\Services\Sms;

use App\Models\SmsSetting;
use App\Models\SmsLog;
use Illuminate\Support\Facades\Http;

class TermiiService
{
    protected string $baseUrl;
    protected string $apiKey;
    protected string $senderId;

    public function __construct()
    {
        $this->baseUrl = config('services.termii.base_uri', 'https://api.ng.termii.com');
        $this->apiKey = env('TERMII_API_KEY');
        $this->senderId = env('TERMII_SENDER_ID');
    }

    public function send(string $phone, string $message, ?int $churchId = null): array
    {
        // Allow per-church override
        if ($churchId) {
            $setting = SmsSetting::query()->where('church_id', $churchId)->where('key', 'termii')->first();
            if ($setting && is_array($setting->value)) {
                $cfg = $setting->value;
                $this->apiKey = $cfg['api_key'] ?? $this->apiKey;
                $this->senderId = $cfg['sender_id'] ?? $this->senderId;
                $this->baseUrl = $cfg['base_uri'] ?? $this->baseUrl;
            }
        }

        $payload = [
            'to' => $phone,
            'from' => $this->senderId,
            'sms' => $message,
            'type' => 'plain',
            'api_key' => $this->apiKey,
        ];

        $response = Http::post(rtrim($this->baseUrl, '/').'/sms/send', $payload);

        $body = $response->json();

        // Normalize
        return [
            'success' => $response->successful() && data_get($body, 'message_id'),
            'body' => $body,
        ];
    }
}
