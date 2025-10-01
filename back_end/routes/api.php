<?php

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\Auth\RegisteredUserController;
use App\Http\Controllers\Auth\AuthenticatedSessionController;
use App\Http\Controllers\AdminController;
use App\Http\Controllers\AlbumController;
use App\Http\Controllers\ChansonController;



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

Route::middleware(['auth:sanctum'])->get('/user', function (Request $request) {
    return $request->user();
});


// register  login logout via breeze 

Route::post('/register', [RegisteredUserController::class, 'store']);

Route::post('/login',[AuthenticatedSessionController::class,'login']);

Route::middleware(['auth:sanctum'])->post('/logout',[AuthenticatedSessionController::class,'destroy']);




// DevelopperController 

Route::middleware(['auth:sanctum','role:admin'])->post('/artists', [AdminController::class,'store']);
Route::get('/show-artists', [AdminController::class,'getArtists']);
Route::middleware(['auth:sanctum','role:admin'])->put('/artists/{id}', [AdminController::class,'updateArtist']);
Route::middleware(['auth:sanctum','role:admin'])->delete('/artists/{id}', [AdminController::class,'deleteArtist']);
Route::get('/artists/{id}', [AdminController::class,'getArtistDetail']);



Route::middleware(['auth:sanctum','role:admin'])->group(function () {
   
    Route::post('/albums', [AlbumController::class,'store']);
    Route::put('/albums/{id}', [AlbumController::class,'update']);
    Route::delete('/albums/{id}', [AlbumController::class,'destroy']);
});

 Route::get('/albums', [AlbumController::class,'index']);
 Route::get('/albums/{id}', [AlbumController::class,'show']);




Route::get('/artists/{id}/albums', [AdminController::class, 'getArtistsWithAlbums']);

Route::get('/artists/{id}/albums-chansons', [AdminController::class, 'getArtistWithAlbumsAndChansons']);


Route::middleware(['auth:sanctum','role:admin'])->group(function() {
    Route::post('/chansons', [ChansonController::class,'store']);
    Route::put('/chansons/{id}', [ChansonController::class,'update']);
    Route::delete('/chansons/{id}', [ChansonController::class,'destroy']);
});
    Route::get('/chansons', [ChansonController::class,'index']);
    Route::get('/chansons/search', [ChansonController::class, 'search']);
    Route::get('/chansons/{id}', [ChansonController::class,'show']);

    Route::get('/albums/{id}/chansons', [ChansonController::class, 'getChansonsByAlbum']);








