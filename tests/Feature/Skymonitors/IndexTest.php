<?php

use App\Livewire\Skymonitors\Index;
use App\Models\Skymonitor;
use App\Models\User;
use Illuminate\Database\Eloquent\Factories\Sequence;
use Livewire\Livewire;

it('exists on page', function () {
    $user = User::factory()->create();

    $this->actingAs($user);
    $this->get('/skymonitors')
        ->assertSeeLivewire(Index::class);
});

it('requires user to be logged to see skymonitors', function () {
    Skymonitor::factory()->create();

    $response = $this->get('/skymonitors');
    $response->assertRedirect('/login');
});

it('shows only skymonitors related to currently logged in user', function () {
    $user = User::factory()->create();
    $otherUser = User::factory()->create();
    Skymonitor::factory()->create(['user_id' => $user->id, 'city' => 'User City']);
    Skymonitor::factory()->create(['user_id' => $otherUser->id, 'city' => 'Other User City']);

    $this->actingAs($user);

    $response = $this->get('/skymonitors');
    $response->assertStatus(200);
    $response->assertSee('User City');
    $response->assertDontSee('Other User City');
});

it('lists paginated skymonitors', function () {
    $user = User::factory()->create();
    Skymonitor::factory()
        ->count(5)
        ->sequence(fn (Sequence $sequence) => ['city' => 'City '.$sequence->index])
        ->create(['user_id' => $user->id]);

    $this->actingAs($user);

    $page1 = $this->get('/skymonitors');
    $page1->assertStatus(200);
    $page1->assertSee('City 0');
    $page1->assertSee('City 1');
    $page1->assertDontSee('City 2');

    $page2 = $this->get('/skymonitors?page=2');
    $page2->assertStatus(200);
    $page2->assertSee('City 2');
    $page2->assertSee('City 3');
    $page2->assertDontSee('City 4');
});

it('deletes skymonitor', function () {
    $user = User::factory()->create();
    $skymonitor = Skymonitor::factory()->create(['user_id' => $user->id]);

    $this->actingAs($user);

    Livewire::test(Index::class, ['skymonitor' => $skymonitor])
        ->call('delete', $skymonitor)
        ->assertHasNoErrors();

    $this->assertDatabaseMissing('skymonitors', ['id' => $skymonitor->id]);
});

it('forbids deletion if user is not the owner', function () {
    $user = User::factory()->create();
    $otherUser = User::factory()->create();
    $skymonitor = Skymonitor::factory()->create(['user_id' => $otherUser->id]);

    $this->actingAs($user);

    Livewire::test(Index::class, ['skymonitor' => $skymonitor])
        ->call('delete', $skymonitor)
        ->assertForbidden();
});
