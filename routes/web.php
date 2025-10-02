<?php

use App\Http\Controllers\AtletaController;
use App\Http\Controllers\AuthController;
use App\Http\Controllers\DashboardController;
use App\Http\Controllers\DivisaoTimesController;
use App\Http\Controllers\PartidaController;
use Illuminate\Support\Facades\Route;

Route::middleware('guest')->group(function () {
    Route::get('login', [AuthController::class, 'showLoginForm'])->name('login');
    Route::post('login', [AuthController::class, 'login']);
});

Route::get('/', function () {

    if (auth()->guest()) {
        return redirect()->route('login');
    }

    return redirect()->route('dashboard');
});


Route::middleware('auth')->group(function () {
    Route::get('/dashboard', [DashboardController::class, 'index'])->name('dashboard');
    Route::resource('atletas', AtletaController::class);
    Route::patch('atletas/{atleta}/toggle-status', [AtletaController::class, 'toggleStatus'])
        ->name('atletas.toggle-status');
    Route::patch('atletas/{id}/restore', [AtletaController::class, 'restore'])
        ->name('atletas.restore')
        ->withTrashed();

    // Partidas routes
    Route::resource('partidas', PartidaController::class);

    // Gerenciamento de atletas na partida
    Route::patch('partidas/{partida}/atletas/{atleta}/toggle-confirmacao', [PartidaController::class, 'toggleConfirmacao'])
        ->name('partidas.toggle-confirmacao');
    Route::post('partidas/{partida}/atletas/adicionar', [PartidaController::class, 'adicionarAtletas'])
        ->name('partidas.adicionar-atletas');
    Route::delete('partidas/{partida}/atletas/{atleta}', [PartidaController::class, 'removerAtleta'])
        ->name('partidas.remover-atleta');

    // Divisão de times
    Route::get('partidas/{partida}/divisao/preview', [DivisaoTimesController::class, 'preview'])
        ->name('partidas.divisao.preview');
    Route::post('partidas/{partida}/divisao/confirmar', [DivisaoTimesController::class, 'confirmar'])
        ->name('partidas.divisao.confirmar');
    Route::post('partidas/{partida}/divisao/redistribuir', [DivisaoTimesController::class, 'redistribuir'])
        ->name('partidas.divisao.redistribuir');
    Route::get('partidas/{partida}/mensagem-whatsapp', [DivisaoTimesController::class, 'gerarMensagem'])
        ->name('partidas.mensagem-whatsapp');

    Route::post('logout', [AuthController::class, 'logout'])->name('logout');
});
