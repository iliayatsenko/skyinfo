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
        $this->authorize('update', $this->form->skymonitorModel);

        $this->form->update();

        return $this->redirectRoute('skymonitors.index', navigate: true);
    }

    #[Layout('layouts.app')]
    public function render()
    {
        $this->authorize('update', $this->form->skymonitorModel);

        return view('livewire.skymonitor.edit');
    }
}
