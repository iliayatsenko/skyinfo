<?php

use App\Livewire\Skymonitors\Create;
use App\Models\User;
use Livewire\Livewire;

it ('exists on page', function () {
    $user = User::factory()->create();

    $this->actingAs($user);

    $this->get('/skymonitors/create')
        ->assertSeeLivewire(Create::class);
});

it('requires user to be logged to create skymonitors', function () {
    $response = $this->get('/skymonitors/create');
    $response->assertRedirect('/login');
});

it('creates skymonitor', function () {
    $user = User::factory()->create();

    $this->actingAs($user);

    Livewire::test(Create::class)
        ->set('form.city', 'Test City')
        ->set('form.email', 'test@example.com')
        ->set('form.phone', '1234567890')
        ->set('form.uv_index_threshold', 5)
        ->set('form.precipitation_threshold', 10)
        ->call('save')
        ->assertHasNoErrors();

    $this->assertDatabaseHas('skymonitors', [
        'user_id' => $user->id,
        'city' => 'Test City',
        'email' => 'test@example.com',
        'phone' => '1234567890',
        'uv_index_threshold' => 5,
        'precipitation_threshold' => 10,
    ]);
});

it('checks that required fields are present', function () {
    $user = User::factory()->create();

    $this->actingAs($user);

    Livewire::test(Create::class)
        ->call('save')
        ->assertHasErrors([
            'form.city' => 'required',
            'form.uv_index_threshold' => 'required',
            'form.precipitation_threshold' => 'required',
        ]);
});

it('checks fields types', function () {
    $user = User::factory()->create();

    $this->actingAs($user);

    Livewire::test(Create::class)
        ->set('form.email', 'invalid-email')
        ->set('form.uv_index_threshold', 'not-numeric')
        ->set('form.precipitation_threshold', 'not-numeric')
        ->call('save')
        ->assertHasErrors([
            'form.email' => 'email',
            'form.uv_index_threshold' => 'numeric',
            'form.precipitation_threshold' => 'numeric',
        ]);
});

it('checks that email or phone is present', function () {
    $user = User::factory()->create();

    $this->actingAs($user);

    Livewire::test(Create::class)
        ->set('form.email', '')
        ->set('form.phone', '')
        ->call('save')
        ->assertHasErrors([
            'form.email' => 'required_without',
            'form.phone' => 'required_without',
        ]);
});

it('allows only one of email or phone to be present', function () {
    $user = User::factory()->create();

    $this->actingAs($user);

    Livewire::test(Create::class)
        ->set('form.city', 'Test City')
        ->set('form.email', 'test@example.com')
        // no phone
        ->set('form.uv_index_threshold', 5)
        ->set('form.precipitation_threshold', 10)
        ->call('save')
        ->assertHasNoErrors();

    Livewire::test(Create::class)
        ->set('form.city', 'Test City')
        // no email
        ->set('form.phone', '1234567890')
        ->set('form.uv_index_threshold', 5)
        ->set('form.precipitation_threshold', 10)
        ->call('save')
        ->assertHasNoErrors();
});
