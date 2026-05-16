<?php

use App\Http\Controllers\RfidController;
use Illuminate\Support\Facades\Route;

Route::post('/iot/rfid-scan', [RfidController::class, 'scan'])
    ->name('api.iot.rfid.scan');
