<?php

use App\Http\Controllers\Api\ClientsController;
use App\Http\Controllers\ClientController;
use App\Http\Controllers\CreateThreadController;
use App\Http\Controllers\DashboardController;
use App\Http\Controllers\DealController;
use App\Http\Controllers\PipelineController;
use App\Http\Controllers\ProfileController;
use App\Http\Controllers\SendMessageController;
use App\Http\Controllers\ShowThreadController;
use App\Http\Controllers\ThreadController;
use Illuminate\Foundation\Application;
use Illuminate\Support\Facades\Route;
use Inertia\Inertia;

Route::get('/', function () {
    return Inertia::render('Welcome', [
        'canLogin' => Route::has('login'),
        'canRegister' => Route::has('register'),
        'laravelVersion' => Application::VERSION,
        'phpVersion' => PHP_VERSION,
    ]);
});





Route::middleware(['auth', 'verified', 'role:sales|manager|super-admin'])
->group(function () {

    Route::get('/dashboard', [DashboardController::class, 'index'])->name('dashboard');

    Route::get('/sales/threads/{thread}', [DashboardController::class, 'show']
    )->name('sales.threads.show');

    Route::post('/sales/threads/{thread}/messages', [DashboardController::class, 'store']);

    Route::post('/sales/threads/{thread}/deals', [DealController::class, 'store']);

    Route::get('/clients', [ClientController::class, 'clients'])->name('clients.index');
    Route::get('/clients/{user}', [ClientController::class, 'show'])
    ->name('clients.show');

    Route::get('/pipeline', [PipelineController::class, 'index'])->name('pipeline');

    Route::get('/deals/{deal}', [DealController::class, 'index'])->name('deals.show');



});



Route::middleware('auth')->group(function () {
    Route::get('/profile', [ProfileController::class, 'edit'])->name('profile.edit');
    Route::patch('/profile', [ProfileController::class, 'update'])->name('profile.update');
    Route::delete('/profile', [ProfileController::class, 'destroy'])->name('profile.destroy');
    Route::post('clients/list', [ClientsController::class, 'listClients']);
    Route::get('pipeline/get', [PipelineController::class, 'getpipeline']);

    Route::get('/deals/{deal}/get', [DealController::class, 'show'])->name('deals.get');

    Route::post('/deals/{deal}/notes', [DealController::class, 'addNote']);

    Route::patch('/deals/{deal}/stage', [DealController::class, 'updateStage']);

    Route::patch('/deals/{deal}/owner', [DealController::class, 'reassignOwner']);

});

Route::middleware(['auth', 'role:client'])
    ->prefix('client')
    ->name('client.')
    ->group(function () {

        Route::get('/dashboard', [ClientController::class, 'index'])
            ->name('dashboard');

        Route::post('/threads', [ClientController::class, 'createThread'])
            ->name('threads.store');

        Route::get('/threads/{thread}', [ClientController::class, 'getThread'])
            ->name('threads.show');

        Route::get('/threads/{thread}/messages', [ClientController::class, 'getThreadMessages'])
            ->name('threads.messages');

        Route::post('/threads/{thread}/messages',[ClientController::class, 'sendMessage'])->name('client.threads.messages.store');
    });


    Route::middleware(['auth', 'role:sales|manager|super-admin'])
    ->prefix('user')
    ->name('user.')
    ->group(function () {
        Route::post(
            '/threads/{thread}/assign',
            [ThreadController::class, 'assign']
        )->name('sales.threads.assign');

    });



require __DIR__.'/auth.php';
