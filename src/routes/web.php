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

Auth::routes(['verify' => true]);

Route::get('/home', [App\Http\Controllers\HomeController::class, 'index'])->name('home');

Route::group([
    'prefix' => 'chat'
], function () {
    Route::get('/', [\App\Http\Controllers\ChatController::class, 'chatPage'])->name('get_chat_page');
    Route::get('/messages/{senderId}', [\App\Http\Controllers\ChatController::class, 'getDialogMessages'])->name('get_dialog_messages');
    Route::post('/send', [\App\Http\Controllers\ChatController::class, 'sendMessage'])->name('send_message');
});
