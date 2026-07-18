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
            'registrations.qrCode',
            'assignments.assignee',
            'notes.user',
        ])->loadCount(['registrations', 'assignments', 'notes']);
    }

    /**
     * Render the component.
     *
     * @return \Illuminate\View\View
     * @noinspection PhpUndefinedMethodInspection
     */
    public function render()
    {
        $timeline = collect()
            ->merge($this->visitor->registrations->map(fn ($registration) => [
                'type' => 'registration',
                'title' => 'First Timer registration completed',
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

        $view = view('livewire.admin.visitor-show', [
            'timeline' => $timeline,
        ]);

        return $this->applyLayout($view);
    }

    private function applyLayout(\Illuminate\View\View $view): \Illuminate\View\View
    {
        /** @noinspection PhpUndefinedMethodInspection */
        return $view->layout('components.layouts.admin', [
            'title' => $this->visitor->full_name,
            'pageTitle' => $this->visitor->full_name,
            'pageDescription' => 'First Timer profile, timeline, assignments, notes, and prayer requests.',
        ]);
    }
}
