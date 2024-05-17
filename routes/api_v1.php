<?php

use App\Http\Controllers\Api\V1\InverterController;
use App\Http\Controllers\Api\V1\InverterOutputController;
use Illuminate\Support\Facades\Route;

Route::apiResource('inverters', InverterController::class);
Route::apiResource('inverter-outputs', InverterOutputController::class);
