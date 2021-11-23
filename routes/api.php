<?php

use App\Models\Note;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\NotesController;
use App\Http\Controllers\FoldersController;

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

// Notes
Route::get('public-notes', [NotesController::class, 'publicNotes']);
Route::get('public-notes/{note}', [NotesController::class, 'showPublicNote']);

Route::middleware(['auth.basic.once'])->group(function (){
    Route::get('notes', [NotesController::class, 'index']);
    Route::get('notes/{note}', [NotesController::class, 'show']);
    Route::post('notes', [NotesController::class, 'store']);
    Route::put('notes/{note}', [NotesController::class, 'update']);
    Route::delete('notes/{note}', [NotesController::class, 'delete']);
});
// Folders
Route::middleware(['auth.basic.once'])->group(function () {
    Route::get('folders', [FoldersController::class, 'index']);
    Route::get('folders/{folder}', [FoldersController::class, 'show']);
    Route::post('folders', [FoldersController::class, 'store']);
    Route::put('folders/{folder}', [FoldersController::class, 'update']);
    Route::delete('folders/{folder}', [FoldersController::class, 'delete']);
});

