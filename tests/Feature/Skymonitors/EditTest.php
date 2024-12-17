<?php

use App\Livewire\Skymonitors\Edit;
use App\Models\Skymonitor;
use App\Models\User;
use Livewire\Livewire;

it ('exists on page', function () {
    $user = User::factory()->create();
    $skymonitor = Skymonitor::factory()->create(['user_id' => $user->id]);

    $this->actingAs($user);

    $this->get('/skymonitors/update/' . $skymonitor->id)
        ->assertSeeLivewire(Edit::class);
});

it('requires user to be logged to update skymonitors', function () {
    $skymonitor = Skymonitor::factory()->create();

    $response = $this->get('/skymonitors/update/' . $skymonitor->id);
    $response->assertRedirect('/login');
});

it('updates skymonitor', function () {
    $user = User::factory()->create();
    $skymonitor = Skymonitor::factory()->create(['user_id' => $user->id]);

    $this->actingAs($user);

    Livewire::test(Edit::class, ['skymonitor' => $skymonitor])
        ->set('form.city', 'Updated City')
        ->set('form.email', 'updated@example.com')
        ->set('form.phone', '0987654321')
        ->set('form.uv_index_threshold', 6)
        ->set('form.precipitation_threshold', 12)
        ->call('save')
        ->assertHasNoErrors();

    $this->assertDatabaseHas('skymonitors', [
        'id' => $skymonitor->id,
        'city' => 'Updated City',
        'email' => 'updated@example.com',
        'phone' => '0987654321',
        'uv_index_threshold' => 6,
        'precipitation_threshold' => 12,
    ]);
});

it('forbids update if user is not the owner', function () {
    $user = User::factory()->create();
    $otherUser = User::factory()->create();
    $skymonitor = Skymonitor::factory()->create(['user_id' => $otherUser->id]);

    $this->actingAs($user);

    $response = $this->get('/skymonitors/update/' . $skymonitor->id);
    $response->assertStatus(403);
});


