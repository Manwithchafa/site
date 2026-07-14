<?php

namespace App\Services\FollowUps;

use App\Models\FollowUp;
use Illuminate\Support\Arr;

class FollowUpService
{
    public function createForVisitor(int $visitorId, array $data = []): FollowUp
    {
        $payload = Arr::only($data, [
            'church_id', 'assigned_to', 'status', 'call_status', 'visit_status', 'notes', 'scheduled_at', 'priority'
        ]);

        $payload['visitor_id'] = $visitorId;

        return FollowUp::create($payload);
    }
}
