<?php

use App\Http\Controllers\Api\AnalyticsController;
use App\Http\Controllers\Api\CertificationCredentialController;
use App\Http\Controllers\Api\ContactController;
use App\Http\Controllers\Api\NewsletterController;
use App\Http\Controllers\Api\PortfolioController;
use Illuminate\Support\Facades\Route;

Route::middleware('throttle:120,1')->group(function () {
    Route::get('/portfolio', [PortfolioController::class, 'index']);
    Route::get('/projects', [PortfolioController::class, 'projects']);
    Route::get('/projects/{slug}', [PortfolioController::class, 'project']);
    Route::get('/certifications/{id}/credential', [CertificationCredentialController::class, 'show'])
        ->whereNumber('id');
});

Route::post('/contact', [ContactController::class, 'store'])
    ->middleware('throttle:6,1');

Route::post('/newsletter', [NewsletterController::class, 'store'])
    ->middleware('throttle:10,1');

Route::post('/analytics', [AnalyticsController::class, 'store'])
    ->middleware('throttle:120,1');
