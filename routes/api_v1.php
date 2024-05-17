<?php

use App\Http\Controllers\Api\V1\InverterController;
use Illuminate\Support\Facades\Route;

Route::apiResource('inverters', InverterController::class);
