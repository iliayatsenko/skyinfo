<?php

namespace App\Livewire\Skymonitors;

use App\Livewire\Forms\SkymonitorForm;
use App\Models\Skymonitor;
use Livewire\Attributes\Layout;
use Livewire\Component;

class Create extends Component
{
    public SkymonitorForm $form;

    public function mount(Skymonitor $skymonitor)
    {
        $this->form->setSkymonitorModel($skymonitor);
    }

    public function save()
    {
        $this->form->store();

        return $this->redirectRoute('skymonitors.index', navigate: true);
    }

    #[Layout('layouts.app')]
    public function render()
    {
        return view('livewire.skymonitor.create');
    }
}
