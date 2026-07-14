<?php

namespace App\Livewire\Admin;

use App\Models\Visitor;
use Illuminate\Contracts\View\View;
use Livewire\Component;

class VisitorShow extends Component
{
    public Visitor $visitor;

    public function mount(Visitor $visitor): void
    {
        $this->visitor = $visitor->load([
            'church',
            'registrations.churchService',
            'registrations.qrCode',
            'assignments.assignee',
            'notes.user',
        ])->loadCount(['registrations', 'assignments', 'notes']);
    }

    public function render(): View
    {
        $timeline = collect()
            ->merge($this->visitor->registrations->map(fn ($registration) => [
                'type' => 'registration',
                'title' => 'Registered for '.$registration->churchService->name,
                'description' => $registration->prayer_request ? 'Prayer request submitted' : 'Visitor registration completed',
                'timestamp' => $registration->created_at,
            ]))
            ->merge($this->visitor->assignments->map(fn ($assignment) => [
                'type' => 'assignment',
                'title' => 'Assigned for follow-up',
                'description' => $assignment->assignee?->name ?? 'No assignee selected',
                'timestamp' => $assignment->assigned_at,
            ]))
            ->merge($this->visitor->notes->map(fn ($note) => [
                'type' => 'note',
                'title' => ucfirst($note->type).' note added',
                'description' => str($note->body)->limit(100),
                'timestamp' => $note->created_at,
            ]))
            ->sortByDesc('timestamp')
            ->values();

        return view('livewire.admin.visitor-show', [
            'timeline' => $timeline,
        ])->layout('components.layouts.admin', [
            'title' => $this->visitor->full_name,
            'pageTitle' => $this->visitor->full_name,
            'pageDescription' => 'Visitor profile, timeline, assignments, notes, and prayer requests.',
        ]);
    }
}
