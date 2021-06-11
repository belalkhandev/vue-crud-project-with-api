<?php

use App\Http\Controllers\Api\CategoryController;
use App\Http\Controllers\Api\ProductController;
use App\Http\Controllers\Api\SubCategoryController;
use App\Http\Controllers\Api\UsersController;
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
    'middleware'=> 'api',
    'prefix' => 'user',
], function ($router) {
    $router->post('register', [UsersController::class, 'register']);
    $router->post('login', [UsersController::class, 'login']);
});

Route::group([
    'middleware'=> 'auth:api',
    'prefix' => 'user'
], function ($router) {
    $router->post('logout', [UsersController::class, 'logout']);
    $router->post('me', [UsersController::class, 'me']);
});

Route::group(['middleware'=> 'auth:api',], function ($router) {
    // category routes
    $router->group(['prefix'=> 'category',], function ($router) {
        $router->get('list', [CategoryController::class, 'index']);
        $router->post('create', [CategoryController::class, 'store']);
        $router->put('edit/{id}', [CategoryController::class, 'update']);
        $router->delete('delete/{id}', [CategoryController::class, 'destroy']);
    });

    // sub category routes
    $router->group(['prefix'=> 'sub-category',], function ($router) {
        $router->get('list', [SubCategoryController::class, 'index']);
        $router->post('create', [SubCategoryController::class, 'store']);
        $router->put('edit/{id}', [SubCategoryController::class, 'update']);
        $router->delete('delete/{id}', [SubCategoryController::class, 'destroy']);
    });

    // products
    $router->group(['prefix'=> 'product',], function ($router) {
        $router->get('list', [ProductController::class, 'index']);
        $router->post('create', [ProductController::class, 'store']);
        $router->put('edit/{id}', [ProductController::class, 'update']);
        $router->delete('delete/{id}', [ProductController::class, 'destroy']);
    });
});

