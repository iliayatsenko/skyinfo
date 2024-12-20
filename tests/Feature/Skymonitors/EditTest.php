<?php

use App\Livewire\Skymonitors\Edit;
use App\Models\Skymonitor;
use App\Models\User;
use Livewire\Livewire;

it('exists on page', function () {
    $user = User::factory()->create();
    $skymonitor = Skymonitor::factory()->create(['user_id' => $user->id]);

    $this->actingAs($user);

    $this->get('/skymonitors/update/'.$skymonitor->id)
        ->assertSeeLivewire(Edit::class);
});

it('requires user to be logged to update skymonitors', function () {
    $skymonitor = Skymonitor::factory()->create();

    $response = $this->get('/skymonitors/update/'.$skymonitor->id);
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

it('forbids update page if user is not the owner', function () {
    $user = User::factory()->create();
    $otherUser = User::factory()->create();
    $skymonitor = Skymonitor::factory()->create(['user_id' => $otherUser->id]);

    $this->actingAs($user);

    $response = $this->get('/skymonitors/update/'.$skymonitor->id);
    $response->assertStatus(403);
});

it('checks that required fields are present', function () {
    $user = User::factory()->create();
    $skymonitor = Skymonitor::factory()->create(['user_id' => $user->id]);

    $this->actingAs($user);

    Livewire::test(Edit::class, ['skymonitor' => $skymonitor])
        ->set('form.email', 'updated@mail.com')
        ->set('form.city', '')
        ->set('form.uv_index_threshold', '')
        ->set('form.precipitation_threshold', '')
        ->call('save')
        ->assertHasErrors([
            'form.city' => 'required',
            'form.uv_index_threshold' => 'required',
            'form.precipitation_threshold' => 'required',
        ]);
});

it('checks fields types', function () {
    $user = User::factory()->create();
    $skymonitor = Skymonitor::factory()->create(['user_id' => $user->id]);

    $this->actingAs($user);

    Livewire::test(Edit::class, ['skymonitor' => $skymonitor])
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
    $skymonitor = Skymonitor::factory()->create(['user_id' => $user->id]);

    $this->actingAs($user);

    Livewire::test(Edit::class, ['skymonitor' => $skymonitor])
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
    $skymonitor = Skymonitor::factory()->create(['user_id' => $user->id]);

    $this->actingAs($user);

    Livewire::test(Edit::class, ['skymonitor' => $skymonitor])
        ->set('form.city', 'Updated City')
        ->set('form.email', 'updated@example.com')
        ->set('form.phone', '')
        ->set('form.uv_index_threshold', 6)
        ->set('form.precipitation_threshold', 12)
        ->call('save')
        ->assertHasNoErrors();

    Livewire::test(Edit::class, ['skymonitor' => $skymonitor])
        ->set('form.city', 'Test City')
        ->set('form.email', '')
        ->set('form.phone', '1234567890')
        ->set('form.uv_index_threshold', 5)
        ->set('form.precipitation_threshold', 10)
        ->call('save')
        ->assertHasNoErrors();
});
