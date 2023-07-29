<?php

use App\Events\Message;
use App\Http\Controllers\ChatController;
use App\Http\Controllers\LoginController;
use App\Http\Controllers\PermissionController;
use App\Http\Controllers\RoleController;
use App\Http\Controllers\SettingController;
use App\Http\Controllers\UserController;
use Illuminate\Support\Facades\App;
use Illuminate\Support\Facades\Auth;
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

Route::get('/admin', function () {
    return view('admin');
})->middleware('auth');

Route::prefix('/admin_vue')->group(function () {
    Route::resource('/user', UserController::class);
    Route::resource('/role', RoleController::class);
    Route::resource('/permission', PermissionController::class);
    Route::resource('/setting', SettingController::class);
    Route::get('auth_user', function () {
        return Auth::user();
    });
    Route::post('get_chat', [ChatController::class, 'chatGet']);
    Route::get('get_chat_read_count', [ChatController::class, 'chatReadCount']);
    Route::post('user_online_status', [ChatController::class, 'userOnlineStatus']);
    Route::resource('chat', ChatController::class);
    Route::get('typing/{fromId}/{toId}', function ($fromId, $toId) {
        event(new \App\Events\TypingCheck($fromId, $toId));
        return response(200);
    });
    Route::post('delete_chat_message', [ChatController::class, 'GroupChatDelete']);

    Route::post('chat_group_add', [ChatController::class, 'ChatGroupAdd']);
    Route::get('get_chat_groups', [ChatController::class, 'ChatGroupGet']);
    Route::post('get_group_chats', [ChatController::class, 'GroupChatGet']);
    Route::post('group_chat', [ChatController::class, 'group_chat_send']);
    Route::get('get_group_chat_read_count', [ChatController::class, 'GroupchatReadCount']);
    Route::post('delete_group_message', [ChatController::class, 'GroupMessageDelete']);
    Route::post('delete_group_coversation', [ChatController::class, 'GroupConversationDelete']);
});

Route::any('admin/{any?}', function () {
    return view('admin');
})->where('any', '.*')->middleware('auth');
Route::get('auth_check', function () {
    return Auth::check() ? 1 : 0;
});

Route::get('loggout', function () {
    return Auth::logout();
});
Route::post('/login_submit', [LoginController::class, 'Login']);
Route::post('/logout', [LoginController::class, 'logout']);
Route::any('login', function () {
    return view('admin');
})->name('login');

Route::post('/register_submit', [LoginController::class, 'Register']);
Route::any('register', function () {
    return view('admin');
})->name('register');



Route::get('/front', function () {
    return view('front');
});
