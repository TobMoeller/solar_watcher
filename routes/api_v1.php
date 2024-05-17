<?php

use App\Http\Controllers\Api\V1\InverterController;
use App\Http\Controllers\Api\V1\InverterOutputController;
use App\Http\Controllers\Api\V1\InverterStatusController;
use Illuminate\Support\Facades\Route;

Route::apiResource('inverters', InverterController::class);
Route::apiResource('inverter-outputs', InverterOutputController::class);
Route::apiResource('inverter-status', InverterStatusController::class);
