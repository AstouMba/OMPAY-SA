<?php

use Illuminate\Support\Facades\Route;
use L5Swagger\Http\Controllers\SwaggerController;

Route::get('/', fn () => view('welcome'));

// Swagger UI
Route::get('/api/documentation', [SwaggerController::class, 'api'])
    ->name('l5-swagger.default.api');

// Fichier JSON Swagger
Route::get('/docs', [SwaggerController::class, 'docs'])
    ->name('l5-swagger.default.docs');

