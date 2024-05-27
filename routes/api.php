<?php

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;

Route::get('resolve', [\App\Http\Controllers\ProxyController::class, 'resolveIp']);
