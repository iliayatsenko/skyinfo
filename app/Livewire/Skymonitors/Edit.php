<?php

namespace App\Livewire\Skymonitors;

use App\Livewire\Forms\SkymonitorForm;
use App\Models\Skymonitor;
use Illuminate\Support\Facades\Gate;
use Livewire\Attributes\Layout;
use Livewire\Component;

class Edit extends Component
{
    public SkymonitorForm $form;

    public function mount(Skymonitor $skymonitor)
    {
        $this->form->setSkymonitorModel($skymonitor);
    }

    public function save()
    {
        Gate::denyIf(fn() => $this->form->skymonitorModel->user_id !== auth()->id());

        $this->form->update();

        return $this->redirectRoute('skymonitors.index', navigate: true);
    }

    #[Layout('layouts.app')]
    public function render()
    {
        Gate::denyIf(fn() => $this->form->skymonitorModel->user_id !== auth()->id());

        return view('livewire.skymonitor.edit');
    }
}
