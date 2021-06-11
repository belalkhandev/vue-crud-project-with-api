<?php

use App\Http\Controllers\Api\UsersController;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;

/*
|--------------------------------------------------------------------------
| API Routes
|--------------------------------------------------------------------------
|
| Here is where you can register API routes for your application. These
| routes are loaded by the RouteServiceProvider within a group which
| is assigned the "api" middleware group. Enjoy building your API!
|
*/

Route::group([
    'middleware'=> 'api'
], function ($router) {
    $router->group(['prefix'=> 'user'], function ($router) {
        $router->post('register', [UsersController::class, 'register']);
        $router->post('login', [UsersController::class, 'login']);
        $router->post('logout', [UsersController::class, 'logout']);
        $router->post('me', [UsersController::class, 'me']);
    });

});
