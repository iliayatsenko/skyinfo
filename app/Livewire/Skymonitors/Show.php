<?php

namespace App\Livewire\Skymonitors;

use App\Livewire\Forms\SkymonitorForm;
use App\Models\Skymonitor;
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
        return view('livewire.skymonitor.show', ['skymonitor' => $this->form->skymonitorModel]);
    }
}
