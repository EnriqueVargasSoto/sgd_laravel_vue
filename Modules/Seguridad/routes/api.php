<?php

use Illuminate\Support\Facades\Route;
use Modules\Seguridad\Http\Controllers\AuthController;
use Modules\Seguridad\Http\Controllers\ModuloController;
use Modules\Seguridad\Http\Controllers\PermisosController;
use Modules\Seguridad\Http\Controllers\RoleController;
use Modules\Seguridad\Http\Controllers\SeguridadController;
use Modules\Seguridad\Http\Controllers\UsuarioController;

/*
 *--------------------------------------------------------------------------
 * API Routes
 *--------------------------------------------------------------------------
 *
 * Here is where you can register API routes for your application. These
 * routes are loaded by the RouteServiceProvider within a group which
 * is assigned the "api" middleware group. Enjoy building your API!
 *
*/

Route::middleware(['auth:sanctum'])->group(function () {
    //Route::apiResource('seguridad', SeguridadController::class)->names('seguridad');
    Route::apiResource('modulos', ModuloController::class);
    Route::get('modulos-inicializa-tabla', [ModuloController::class, 'incializaTabla']);
});

Route::apiResource('permisos', PermisosController::class);
Route::get('permisos-inicializa-tabla', [PermisosController::class, 'incializaTabla']);

Route::apiResource('roles', RoleController::class);
Route::get('roles-inicializa-tabla', [RoleController::class, 'incializaTabla']);



Route::apiResource('usuarios', UsuarioController::class);
Route::get('usuarios-inicializa-tabla', [UsuarioController::class, 'incializaTabla']);

Route::post('login', [AuthController::class, 'login']);

