<?php

use App\Http\Controllers\InverterController;
use App\Livewire\Inverters\InverterList;
use App\Livewire\Inverters\InverterShow;
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
Route::get('/guests/inverter/{inverter}', InverterShow::class)
    ->name('guests.inverters.show');
Route::get('/guests/inverters/combined', [InverterController::class, 'showCombined'])
    ->name('guests.inverters.show.combined');
