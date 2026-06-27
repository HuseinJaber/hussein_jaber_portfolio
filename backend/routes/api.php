<?php

use App\Http\Controllers\Api\ContactController;
use App\Http\Controllers\Api\PortfolioController;
use Illuminate\Support\Facades\Route;

Route::get('/portfolio', [PortfolioController::class, 'index']);
Route::get('/projects', [PortfolioController::class, 'projects']);
Route::get('/projects/{slug}', [PortfolioController::class, 'project']);

Route::post('/contact', [ContactController::class, 'store'])
    ->middleware('throttle:6,1');
