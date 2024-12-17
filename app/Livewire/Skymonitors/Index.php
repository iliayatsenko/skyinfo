<?php

namespace App\Livewire\Skymonitors;

use App\Models\Skymonitor;
use Illuminate\Support\Facades\Gate;
use Illuminate\View\View;
use Livewire\Attributes\Layout;
use Livewire\Component;
use Livewire\WithPagination;

class Index extends Component
{
    use WithPagination;

    #[Layout('layouts.app')]
    public function render(): View
    {
        $skymonitors = Skymonitor::where('user_id', auth()->id())
            ->latest()
            ->paginate(2);

        return view('livewire.skymonitor.index', compact('skymonitors'))
            ->with('i', $this->getPage() * $skymonitors->perPage());
    }

    public function delete(Skymonitor $skymonitor)
    {
        Gate::denyIf(fn() => $skymonitor->user_id !== auth()->id());

        $skymonitor->delete();

        return $this->redirectRoute('skymonitors.index', navigate: true);
    }
}
