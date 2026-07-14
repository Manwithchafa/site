<?php

namespace App\Jobs;

use App\Models\SmsLog;
use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Foundation\Bus\Dispatchable;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Queue\SerializesModels;

class RetryFailedSmsJob implements ShouldQueue
{
    use Dispatchable, InteractsWithQueue, Queueable, SerializesModels;

    public int $maxAttemptsPerLog = 3;

    public function handle()
    {
        $failed = SmsLog::query()
            ->where('status', 'failed')
            ->where('attempts', '<', $this->maxAttemptsPerLog)
            ->limit(100)
            ->get();

        foreach ($failed as $log) {
            // Dispatch a SendSmsJob with the log's details
            SendSmsJob::dispatch($log->phone, $log->message, $log->church_id, $log->visitor_id, $log->template_id);
        }
    }
}
