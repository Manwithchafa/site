<?php

namespace App\Jobs;

use App\Models\SmsLog;
use App\Services\Sms\TermiiService;
use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Foundation\Bus\Dispatchable;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Queue\SerializesModels;

class SendSmsJob implements ShouldQueue
{
    use Dispatchable, InteractsWithQueue, Queueable, SerializesModels;

    public string $phone;
    public string $message;
    public ?int $churchId;
    public ?int $visitorId;
    public ?int $templateId;

    public function __construct(string $phone, string $message, ?int $churchId = null, ?int $visitorId = null, ?int $templateId = null)
    {
        $this->phone = $phone;
        $this->message = $message;
        $this->churchId = $churchId;
        $this->visitorId = $visitorId;
        $this->templateId = $templateId;
    }

    public function handle(TermiiService $termii)
    {
        // Create a log with pending status
        $log = SmsLog::create([
            'church_id' => $this->churchId,
            'visitor_id' => $this->visitorId,
            'phone' => $this->phone,
            'message' => $this->message,
            'template_id' => $this->templateId,
            'status' => 'pending',
            'attempts' => 0,
        ]);

        try {
            $result = $termii->send($this->phone, $this->message, $this->churchId);

            if ($result['success']) {
                $log->update([
                    'status' => 'sent',
                    'external_id' => data_get($result, 'body.message_id') ?? data_get($result, 'body.data.message_id'),
                    'sent_at' => now(),
                    'attempts' => $log->attempts + 1,
                ]);
            } else {
                $log->update([
                    'status' => 'failed',
                    'error' => json_encode($result['body'] ?? ['error' => 'unknown']),
                    'attempts' => $log->attempts + 1,
                ]);
                // Let the job fail silently; retry policy can be defined in queue config
            }
        } catch (\Throwable $e) {
            $log->update([
                'status' => 'failed',
                'error' => $e->getMessage(),
                'attempts' => $log->attempts + 1,
            ]);
            report($e);
        }
    }
}
