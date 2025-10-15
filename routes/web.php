<?php

use Illuminate\Support\Facades\Route;

/*
|--------------------------------------------------------------------------
| Web Routes
|--------------------------------------------------------------------------
|
| Here is where you can register web routes for your application. These
| routes are loaded by the RouteServiceProvider and all of them will
| be assigned to the "web" middleware group. Make something great!
|
*/

Route::get('/', function () {
    return view('welcome');
});

// Custom route for Swagger JSON
Route::get('/docs/api-docs.json', function () {
    $path = storage_path('api-docs/api-docs.json');
    if (file_exists($path)) {
        return response()->file($path, [
            'Content-Type' => 'application/json',
        ]);
    }
    return response()->json(['error' => 'Documentation not found'], 404);
});

// Custom Swagger UI
Route::get('/api/documentation', [\App\Http\Controllers\CustomSwaggerController::class, 'api']);
