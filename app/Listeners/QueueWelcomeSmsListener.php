<?php

namespace App\Listeners;

use App\Events\VisitorRegistered;
use App\Jobs\SendSmsJob;
use App\Models\SmsTemplate;
use Illuminate\Contracts\Queue\ShouldQueue;

class QueueWelcomeSmsListener implements ShouldQueue
{
    public function handle(VisitorRegistered $event): void
    {
        $registration = $event->registration;

        // Load visitor and church
        $visitor = $registration->visitor;
        $churchId = $registration->church_id;

        if (! $visitor || ! $visitor->phone) {
            return;
        }

        // Find an active welcome template for the church, fallback to global
        $template = SmsTemplate::query()
            ->where('church_id', $churchId)
            ->where('slug', 'welcome')
            ->orWhere(function ($q) use ($churchId) {
                $q->whereNull('church_id')->where('slug', 'welcome');
            })
            ->first();

        if (! $template) {
            return;
        }

        // Render simple variable replacements (visitor.first_name, church_name)
        $message = $template->body;
        $message = str_replace('{{first_name}}', $visitor->first_name ?? '', $message);

        // attempt to inject church name placeholder if present
        $churchName = $registration->church->name ?? null;
        if ($churchName) {
            $message = str_replace('{{church_name}}', $churchName, $message);
        }

        // Queue SMS job (always queued)
        SendSmsJob::dispatch($visitor->phone, $message, $churchId, $visitor->id, $template->id);
    }
}
