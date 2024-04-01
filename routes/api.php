<?php

use App\Http\Controllers\Api\Auth\AuthController;
use App\Http\Controllers\Api\v1\TaskController;
use Illuminate\Http\Request;
use Illuminate\Routing\Router;
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

Route::middleware('auth:sanctum')->get('/user', function (Request $request) {
    return $request->user();
});

Route::group([
    'prefix' => 'auth',
    'middleware' => 'api',
    'controller' => TaskController::class,
], function (Router $router) {
    $router->post('login', AuthController::class . '@login');

    $router->post('refresh-token', AuthController::class . '@refresh');
});

Route::group([
    'prefix' => 'v1'
], function (Router $router) {
    $router->group([
        'middleware' => 'auth:api',
        'controller' => TaskController::class,
        'prefix' => 'tasks'
    ], function (Router $router) {
        $router->get('', 'index');
        $router->get('{task}', 'show');
        $router->put('{task}', 'update');
        $router->post('', 'store');
        $router->delete('{task}', 'destroy');
    });
});
