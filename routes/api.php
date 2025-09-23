<?php

use App\Http\Controllers\Api\PortfolioApiController;
use Illuminate\Support\Facades\Route;

// Public API routes for React frontend
Route::prefix('v1')->group(function () {
    // Get all portfolio data
    Route::get('/portfolio', [PortfolioApiController::class, 'getFullPortfolio']);
    
    // Get data by service - now accepts both ID and slug but parameter name is explicit
    Route::get('/services/{serviceId}/portfolio', [PortfolioApiController::class, 'getServicePortfolio']);
    
    // Individual resource endpoints
    Route::get('/services', [PortfolioApiController::class, 'getServices']);
    Route::get('/services/{serviceId}/projects', [PortfolioApiController::class, 'getProjects']);
    Route::get('/services/{serviceId}/experiences', [PortfolioApiController::class, 'getExperiences']);
    Route::get('/services/{serviceId}/certificates', [PortfolioApiController::class, 'getCertificates']);
    Route::get('/services/{serviceId}/awards', [PortfolioApiController::class, 'getAwards']);
    
    // Get specific items
    Route::get('/projects/{id}', [PortfolioApiController::class, 'getProject']);
    Route::get('/experiences/{id}', [PortfolioApiController::class, 'getExperience']);
    Route::get('/certificates/{id}', [PortfolioApiController::class, 'getCertificate']);
    Route::get('/awards/{id}', [PortfolioApiController::class, 'getAward']);
});