<?php

use Illuminate\Support\Facades\Route;
use Modules\Maestros\Http\Controllers\EstadoController;
use Modules\Maestros\Http\Controllers\ImportanciaController;
use Modules\Maestros\Http\Controllers\MaestrosController;
use Modules\Maestros\Http\Controllers\OficinaController;
use Modules\Maestros\Http\Controllers\TipoDocumentoController;
use Modules\Maestros\Http\Controllers\TipoDocumentoIdentidadController;
use Modules\Maestros\Http\Controllers\TipoMovimientoController;
use Modules\Maestros\Http\Controllers\UnidadOrganicaController;

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

Route::middleware(['auth:sanctum'])->prefix('v1')->group(function () {
    Route::apiResource('maestros', MaestrosController::class)->names('maestros');
});

Route::apiResource('oficinas', OficinaController::class);
Route::get('oficinas-inicializa-tabla', [OficinaController::class, 'incializaTabla']);

Route::apiResource('tipos-documento', TipoDocumentoController::class);
Route::get('tipos-documento-inicializa-tabla', [TipoDocumentoController::class, 'incializaTabla']);

Route::apiResource('tipos-movimiento', TipoMovimientoController::class);
Route::get('tipos-movimiento-inicializa-tabla', [TipoMovimientoController::class, 'incializaTabla']);

Route::apiResource('estados', EstadoController::class);
Route::get('estados-inicializa-tabla', [EstadoController::class, 'incializaTabla']);

Route::apiResource('importancias', ImportanciaController::class);
Route::get('importancias-inicializa-tabla', [ImportanciaController::class, 'incializaTabla']);

Route::apiResource('unidades-organicas', UnidadOrganicaController::class);
//Route::get('importancias-inicializa-tabla', [ImportanciaController::class, 'incializaTabla']);

Route::apiResource('tipos-documentos-identidad', TipoDocumentoIdentidadController::class);
//Route::get('importancias-inicializa-tabla', [ImportanciaController::class, 'incializaTabla']);
