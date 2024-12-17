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
        ->set('form.user_id', $user->id)
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
