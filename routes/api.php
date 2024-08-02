<?php

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;

// Example route
Route::get('/example', function (Request $request) {
    return ['message' => 'API route is working!'];
});
