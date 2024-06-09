<?php

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;

Route::post('resolve', [\App\Http\Controllers\ProxyController::class, 'resolveIp']);
Route::post('check', [\App\Http\Controllers\ProxyController::class, 'check']);
