<?php

use App\Http\Controllers\ChatController;
use App\Http\Controllers\DocumentController;
use App\Http\Controllers\ProfileController;
use App\Http\Controllers\SandboxController;
use Illuminate\Foundation\Application;
use Illuminate\Support\Facades\Route;
use Inertia\Inertia;

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
    return Inertia::render('Dashboard', [
        'user' => request()->user(),
        'files' => [1]
    ]);
})->middleware(['auth', 'verified'])->name('dashboard');

Route::middleware('auth')->group(function () {
    Route::resource('documents', DocumentController::class)->only(['index', 'store', 'show', 'destroy']);

    #region ------------------ Chat ------------------

    Route::get('/api/chat', ChatController::class)->name('chat');

    #endregion ------------------ Chat ------------------

    #region ------------------ Sandbox ------------------

    Route::prefix('/sandbox')->group(function () {
        Route::get('/healthcheck', [SandboxController::class, 'llmHealthcheck']);
        Route::get('/embedding', [SandboxController::class, 'llmEmbedding']);
        Route::get('/document-upload', [SandboxController::class, 'llmDocumentUpload']);
        Route::get('/text-upload', [SandboxController::class, 'llmTextUpload']);
        Route::get('/document-delete/{uuid}', [SandboxController::class, 'llmDeleteDocument']);
        Route::get('/document-list', [SandboxController::class, 'llmDocumentList']);
        Route::post('/completion', [SandboxController::class, 'llmCompletion']);
    });

    #endregion ------------------ Sandbox ------------------

    #region ------------------ Profile ------------------

    Route::get('/profile', [ProfileController::class, 'edit'])->name('profile.edit');
    Route::patch('/profile', [ProfileController::class, 'update'])->name('profile.update');
    Route::delete('/profile', [ProfileController::class, 'destroy'])->name('profile.destroy');

    #endregion ------------------ Profile ------------------

});

require __DIR__ . '/auth.php';
