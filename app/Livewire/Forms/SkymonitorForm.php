<?php

namespace App\Livewire\Forms;

use App\Models\Skymonitor;
use Livewire\Form;

class SkymonitorForm extends Form
{
    public ?Skymonitor $skymonitorModel;

    public $user_id = '';
    public $city = '';
    public $email = '';
    public $phone = '';
    public $uv_index_threshold = '';
    public $precipitation_threshold = '';

    public function rules(): array
    {
        return [
            'user_id' => 'required|numeric',
            'city' => 'required|string',
            'email' => 'nullable|required_without:phone|string|email',
            'phone' => 'nullable|required_without:email|string',
            'uv_index_threshold' => 'required|numeric',
            'precipitation_threshold' => 'required|numeric',
        ];
    }

    public function setSkymonitorModel(Skymonitor $skymonitorModel): void
    {
        $this->skymonitorModel = $skymonitorModel;

        $this->user_id = $this->skymonitorModel->user_id;
        $this->city = $this->skymonitorModel->city;
        $this->email = $this->skymonitorModel->email ?? auth()->user()->email;
        $this->phone = $this->skymonitorModel->phone;
        $this->uv_index_threshold = $this->skymonitorModel->uv_index_threshold;
        $this->precipitation_threshold = $this->skymonitorModel->precipitation_threshold;
    }

    public function store(): void
    {
        $this->user_id = auth()->id();
        $this->skymonitorModel->create($this->validate());

        $this->reset();
    }

    public function update(): void
    {
        $this->skymonitorModel->update($this->validate());

        $this->reset();
    }
}
