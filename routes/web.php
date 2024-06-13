<?php

use App\Livewire\Inverters\InverterList;
use Illuminate\Support\Facades\Route;

Route::redirect('/', '/guests/inverters');

Route::middleware([
    'auth:sanctum',
    config('jetstream.auth_session'),
    'verified',
])->group(function () {
    Route::get('/dashboard', function () {
        return view('dashboard');
    })->name('dashboard');
});

Route::get('/guests/inverters', InverterList::class)
    ->name('guests.inverters.list');
