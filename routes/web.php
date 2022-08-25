<?php

use Illuminate\Support\Facades\Route;

/*
|--------------------------------------------------------------------------
| Web Routes
|--------------------------------------------------------------------------
|
| Here is where you can register web routes for your application. These
| routes are loaded by the RouteServiceProvider within a group which
| contains the "web" middleware group. Now create something great!
|
*/

Route::get('/', function () {
    return view('welcome');
});

Auth::routes();

Route::post('/broadcasting/auth', function () {
    return Auth::user();
 });

Route::get('/chat', [App\Http\Controllers\ChatController::class, 'index'])->name('chat');
Route::get('/user/list', [App\Http\Controllers\ChatController::class, 'userList'])->name('user-list');
Route::get('/chat/{id}', [App\Http\Controllers\ChatController::class, 'chat'])->name('chat-to-friend');
Route::post('/chat/{id}', [App\Http\Controllers\ChatController::class, 'sentMessage'])->name('send-message');
