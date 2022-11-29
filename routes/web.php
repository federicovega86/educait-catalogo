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


############################
####### Marcas

use App\Http\Controllers\MarcaController;
//index
Route::get( '/marcas', [ MarcaController::class, 'index' ] );
//create
Route::get( '/marca/create', [ MarcaController::class, 'create' ] );
//store
Route::post( '/marca/store', [ MarcaController::class, 'store' ] );
// edit
Route::get( '/marca/edit/{idMarca}', [ MarcaController::class, 'edit' ] );
//update
Route::patch('/marca/update/', [ MarcaController::class, 'update' ] );
//delete
Route::get( '/marca/delete/{idMarca}', [ MarcaController::class, 'confirm' ] );
//destroy   
Route::delete( '/marca/destroy', [ MarcaController::class, 'destroy' ] );

#####################
## crud de categorias
use App\Http\Controllers\CategoriaController;
//index
Route::get( '/categorias', [ CategoriaController::class, 'index' ] );
//create
Route::get( '/categoria/create', [ CategoriaController::class, 'create' ] );
//store
Route::post( '/categoria/store', [ CategoriaController::class, 'store' ] );
// edit
Route::get( '/categoria/edit/{idCategoria}', [ CategoriaController::class, 'edit' ] );
//update
Route::patch('/categoria/update/', [ CategoriaController::class, 'update' ] );
//delete
Route::get( '/categoria/delete/{idCategoria}', [ CategoriaController::class, 'confirm' ] );
//destroy
Route::delete( '/categoria/destroy', [ CategoriaController::class, 'destroy' ] );


#####################
## crud de productos
use App\Http\Controllers\ProductoController;
//index
Route::get('/productos', [ProductoController::class, 'index']);
//create
Route::get('/producto/create', [ProductoController::class, 'create']);
//store
Route::post('/producto/store', [ProductoController::class, 'store']);
//edit
Route::get('/producto/edit/{idProducto}', [ProductoController::class, 'edit']);
//update
Route::patch('/producto/update', [ProductoController::class, 'update']);
//delete
Route::get('/producto/delete/{idProducto}', [ProductoController::class, 'confirm']);
//destroy
Route::delete('/producto/destroy', [ProductoController::class, 'destroy']);
