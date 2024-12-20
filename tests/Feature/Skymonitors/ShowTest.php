<?php

use App\Livewire\Skymonitors\Show;
use App\Models\Skymonitor;
use App\Models\User;
use Livewire\Livewire;

it('exists on page', function () {
    $user = User::factory()->create();
    $skymonitor = Skymonitor::factory()->create(['user_id' => $user->id]);

    $this->actingAs($user);

    $this->get('/skymonitors/show/'.$skymonitor->id)
        ->assertSeeLivewire(Show::class);
});

it('requires user to be logged to see skymonitor', function () {
    $skymonitor = Skymonitor::factory()->create();

    $response = $this->get('/skymonitors/show/'.$skymonitor->id);
    $response->assertRedirect('/login');
});

it('shows skymonitor', function () {
    $user = User::factory()->create();
    $skymonitor = Skymonitor::factory()->create(['user_id' => $user->id]);

    $this->actingAs($user);

    Livewire::test(Show::class, ['skymonitor' => $skymonitor])
        ->assertSee($skymonitor->city)
        ->assertSee($skymonitor->email)
        ->assertSee($skymonitor->phone)
        ->assertSee($skymonitor->uv_index_threshold)
        ->assertSee($skymonitor->precipitation_threshold);
});

it('forbids access to skymonitor if user is not the owner', function () {
    $user = User::factory()->create();
    $otherUser = User::factory()->create();
    $skymonitor = Skymonitor::factory()->create(['user_id' => $otherUser->id]);

    $this->actingAs($user);

    $response = $this->get('/skymonitors/show/'.$skymonitor->id);
    $response->assertStatus(403);
});
