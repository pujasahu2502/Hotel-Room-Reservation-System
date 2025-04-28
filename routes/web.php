<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\RoomController;

Route::get('/', [RoomController::class, 'index']);
Route::post('/book', [RoomController::class, 'bookRooms']);
Route::post('/random', [RoomController::class, 'generateRandomOccupancy']);
Route::post('/reset', [RoomController::class, 'resetBookings']);