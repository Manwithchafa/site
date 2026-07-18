<?php

namespace App\Livewire\Admin;

use App\Models\Visitor;
use Illuminate\Contracts\Database\Eloquent\Builder;
use Illuminate\Contracts\View\View;
use Illuminate\Validation\Rule;
use Livewire\Attributes\Url;
use Livewire\Component;
use Livewire\WithPagination;

class VisitorIndex extends Component
{
    use WithPagination;

    #[Url(as: 'q')]
    public string $search = '';

    #[Url]
    public string $sex = '';

    #[Url]
    public string $membership = '';

    #[Url]
    public string $bornAgain = '';

    #[Url]
    public string $registered = '';

    public int $perPage = 10;

    public ?int $editingVisitorId = null;

    public string $edit_first_name = '';

    public string $edit_last_name = '';

    public string $edit_sex = '';

    public ?string $edit_date_of_birth = null;

    public string $edit_phone = '';

    public ?string $edit_email = null;

    public ?string $edit_residential_address = null;

    public ?string $edit_nearest_bus_stop = null;

    public ?string $edit_occupation = null;

    public ?string $edit_invited_by = null;

    public bool $edit_born_again = false;

    public bool $edit_wants_membership = false;

    public string $edit_status = 'new';

    public function updated(string $property): void
    {
        if (in_array($property, ['search', 'sex', 'membership', 'bornAgain', 'registered'], true)) {
            $this->resetPage();
        }
    }

    public function clearFilters(): void
    {
        $this->reset(['search', 'sex', 'membership', 'bornAgain', 'registered']);
        $this->resetPage();
    }

    public function editVisitor(int $visitorId): void
    {
        $visitor = Visitor::query()->findOrFail($visitorId);

        $this->editingVisitorId = $visitor->id;
        $this->edit_first_name = $visitor->first_name;
        $this->edit_last_name = $visitor->last_name;
        $this->edit_sex = $visitor->sex;
        $this->edit_date_of_birth = $visitor->date_of_birth?->toDateString();
        $this->edit_phone = $visitor->phone;
        $this->edit_email = $visitor->email;
        $this->edit_residential_address = $visitor->residential_address;
        $this->edit_nearest_bus_stop = $visitor->nearest_bus_stop;
        $this->edit_occupation = $visitor->occupation;
        $this->edit_invited_by = $visitor->invited_by;
        $this->edit_born_again = $visitor->born_again;
        $this->edit_wants_membership = $visitor->wants_membership;
        $this->edit_status = $visitor->status;

        $this->resetValidation();
    }

    public function cancelEdit(): void
    {
        $this->resetEditForm();
    }

    public function updateVisitor(): void
    {
        $validated = collect($this->validate($this->visitorRules()))
            ->mapWithKeys(fn ($value, $key) => [str($key)->after('edit_')->toString() => $value])
            ->all();

        Visitor::query()
            ->findOrFail($this->editingVisitorId)
            ->update($validated);

        $this->resetEditForm();
        session()->flash('status', 'Visitor profile updated.');
    }

    public function deleteVisitor(int $visitorId): void
    {
        Visitor::query()->findOrFail($visitorId)->delete();

        if ($this->editingVisitorId === $visitorId) {
            $this->resetEditForm();
        }

        $this->resetPage();
        session()->flash('status', 'Visitor profile deleted.');
    }

    /**
     * @return array<string, mixed>
     */
    protected function visitorRules(): array
    {
        return [
            'edit_first_name' => ['required', 'string', 'max:255'],
            'edit_last_name' => ['required', 'string', 'max:255'],
            'edit_sex' => ['required', Rule::in(['female', 'male'])],
            'edit_date_of_birth' => ['nullable', 'date'],
            'edit_phone' => ['required', 'string', 'max:255'],
            'edit_email' => ['nullable', 'email', 'max:255'],
            'edit_residential_address' => ['nullable', 'string'],
            'edit_nearest_bus_stop' => ['nullable', 'string', 'max:255'],
            'edit_occupation' => ['nullable', 'string', 'max:255'],
            'edit_invited_by' => ['nullable', 'string', 'max:255'],
            'edit_born_again' => ['boolean'],
            'edit_wants_membership' => ['boolean'],
            'edit_status' => ['required', Rule::in(['new', 'contacted', 'assigned', 'member', 'inactive'])],
        ];
    }

    protected function resetEditForm(): void
    {
        $this->reset([
            'editingVisitorId',
            'edit_first_name',
            'edit_last_name',
            'edit_sex',
            'edit_date_of_birth',
            'edit_phone',
            'edit_email',
            'edit_residential_address',
            'edit_nearest_bus_stop',
            'edit_occupation',
            'edit_invited_by',
            'edit_born_again',
            'edit_wants_membership',
            'edit_status',
        ]);

        $this->edit_status = 'new';
        $this->resetValidation();
    }

    public function render(): View
    {
        $visitors = Visitor::query()
            ->with(['church', 'registrations.churchService'])
            ->withCount(['registrations', 'assignments', 'notes'])
            ->when($this->search !== '', function (Builder $query) {
                $search = trim($this->search);

                $query->where(function (Builder $query) use ($search) {
                    $query
                        ->where('first_name', 'like', "%{$search}%")
                        ->orWhere('last_name', 'like', "%{$search}%")
                        ->orWhere('phone', 'like', "%{$search}%")
                        ->orWhere('email', 'like', "%{$search}%")
                        ->orWhere('nearest_bus_stop', 'like', "%{$search}%")
                        ->orWhere('occupation', 'like', "%{$search}%");
                });
            })
            ->when($this->sex !== '', fn (Builder $query) => $query->where('sex', $this->sex))
            ->when($this->membership !== '', fn (Builder $query) => $query->where('wants_membership', $this->membership === 'yes'))
            ->when($this->bornAgain !== '', fn (Builder $query) => $query->where('born_again', $this->bornAgain === 'yes'))
            ->when($this->registered !== '', function (Builder $query) {
                $query->whereHas('registrations', function (Builder $query) {
                    match ($this->registered) {
                        'today' => $query->whereDate('registered_on', today()),
                        'week' => $query->whereDate('registered_on', '>=', now()->startOfWeek()->toDateString()),
                        'month' => $query->whereDate('registered_on', '>=', now()->startOfMonth()->toDateString()),
                        default => null,
                    };
                });
            })
            ->latest()
            ->paginate($this->perPage);

        /** @var \Illuminate\View\View $view */
        $view = view('livewire.admin.visitor-index', [
            'visitors' => $visitors,
        ]);

        /** @noinspection PhpUndefinedMethodInspection */
        return $view->layout('components.layouts.admin', [
            'title' => 'Visitors',
            'pageTitle' => 'Visitors',
            'pageDescription' => 'Search, filter, review, and open visitor profiles.',
        ]);
    }
}
