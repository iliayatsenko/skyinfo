<?php

namespace App\Livewire\Skymonitors;

use App\Livewire\Forms\SkymonitorForm;
use App\Models\Skymonitor;
use Illuminate\Support\Facades\Gate;
use Livewire\Attributes\Layout;
use Livewire\Component;

class Show extends Component
{
    public SkymonitorForm $form;

    public function mount(Skymonitor $skymonitor)
    {
        $this->form->setSkymonitorModel($skymonitor);
    }

    #[Layout('layouts.app')]
    public function render()
    {
        $this->authorize('view', $this->form->skymonitorModel);

        return view('livewire.skymonitor.show', ['skymonitor' => $this->form->skymonitorModel]);
    }
}
