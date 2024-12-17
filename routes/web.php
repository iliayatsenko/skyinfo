<?php

use App\Livewire\Skymonitors\Create;
use App\Livewire\Skymonitors\Edit;
use App\Livewire\Skymonitors\Index;
use App\Livewire\Skymonitors\Show;
use Illuminate\Support\Facades\Route;

Route::view('/', 'welcome');

Route::view('profile', 'profile')
    ->middleware(['auth'])
    ->name('profile');

Route::get('skymonitors.index', Index::class)
    ->middleware(['auth', 'verified'])
    ->name('skymonitors.index');

Route::get('/skymonitors/create', Create::class)
    ->middleware(['auth', 'verified'])
    ->name('skymonitors.create');

Route::get('/skymonitors/show/{skymonitor}', Show::class)
    ->middleware(['auth', 'verified'])
    ->name('skymonitors.show');

Route::get('/skymonitors/update/{skymonitor}', Edit::class)
    ->middleware(['auth', 'verified'])
    ->name('skymonitors.edit');

require __DIR__.'/auth.php';
