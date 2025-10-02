@extends('layouts.app')

@section('title', 'Dividir Times - Futsal Manager')

@section('breadcrumb')
    <li><a href="{{ route('dashboard') }}">Dashboard</a></li>
    <li><a href="{{ route('partidas.index') }}">Partidas</a></li>
    <li><a href="{{ route('partidas.show', $partida) }}">{{ $partida->data_formatada }}</a></li>
    <li>Divisão de Times</li>
@endsection

@section('header')
    <div class="flex flex-col lg:flex-row lg:items-center lg:justify-between gap-4">
        <div>
            <h1 class="text-3xl font-bold text-gray-900">⚖️ Divisão de Times</h1>
            <p class="text-gray-600 mt-1">{{ $partida->data_hora_formatada }} - {{ $partida->local }}</p>
        </div>
    </div>
@endsection

@section('content')
    <div class="max-w-6xl mx-auto space-y-6">
        <!-- Alertas e Informações -->
        <div class="alert alert-info">
            <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24"
                class="stroke-current shrink-0 w-6 h-6">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                    d="M13 16h-1v-4h-1m1-4h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z"></path>
            </svg>
            <div>
                <h3 class="font-bold">Algoritmo de Balanceamento</h3>
                <div class="text-sm">
                    Os times foram divididos usando o método <strong>Snake Draft</strong> baseado no nível de habilidade dos
                    jogadores.
                    Isso garante que ambos os times tenham habilidades equilibradas.
                </div>
            </div>
        </div>

        <!-- Estatísticas de Balanceamento -->
        <div class="grid grid-cols-1 md:grid-cols-3 gap-4">
            <div class="stat bg-neutral text-neutral-content rounded-xl shadow-lg">
                <div class="stat-figure text-neutral-content opacity-60">
                    <div class="text-3xl">⚫</div>
                </div>
                <div class="stat-title text-neutral-content opacity-90">Time Preto</div>
                <div class="stat-value">{{ $divisao['estatisticas']['preto']['total'] }}</div>
                <div class="stat-desc text-neutral-content opacity-75">
                    Soma: {{ $divisao['estatisticas']['preto']['soma'] }} |
                    Média: {{ $divisao['estatisticas']['preto']['media'] }} ⭐
                </div>
            </div>

            <div class="stat bg-base-200 rounded-xl shadow-lg">
                <div class="stat-figure text-primary">
                    <svg class="w-8 h-8" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                            d="M9 19v-6a2 2 0 00-2-2H5a2 2 0 00-2 2v6a2 2 0 002 2h2a2 2 0 002-2zm0 0V9a2 2 0 012-2h2a2 2 0 012 2v10m-6 0a2 2 0 002 2h2a2 2 0 002-2m0 0V5a2 2 0 012-2h2a2 2 0 012 2v14a2 2 0 01-2 2h-2a2 2 0 01-2-2z" />
                    </svg>
                </div>
                <div class="stat-title">Balanceamento</div>
                <div class="stat-value text-2xl">
                    @if ($divisao['estatisticas']['diferenca_media'] < 0.5)
                        <span class="text-success">Excelente</span>
                    @elseif($divisao['estatisticas']['diferenca_media'] < 1.0)
                        <span class="text-info">Bom</span>
                    @else
                        <span class="text-warning">Aceitável</span>
                    @endif
                </div>
                <div class="stat-desc">
                    Diferença de média: {{ number_format($divisao['estatisticas']['diferenca_media'], 2) }} ⭐
                </div>
            </div>

            <div class="stat bg-base-100 border-2 rounded-xl shadow-lg">
                <div class="stat-figure">
                    <div class="text-3xl">⚪</div>
                </div>
                <div class="stat-title">Time Branco</div>
                <div class="stat-value">{{ $divisao['estatisticas']['branco']['total'] }}</div>
                <div class="stat-desc">
                    Soma: {{ $divisao['estatisticas']['branco']['soma'] }} |
                    Média: {{ $divisao['estatisticas']['branco']['media'] }} ⭐
                </div>
            </div>
        </div>

        <!-- Preview dos Times -->
        <div class="grid grid-cols-1 lg:grid-cols-2 gap-6">
            <!-- Time Preto -->
            <div class="card bg-neutral text-neutral-content shadow-2xl">
                <div class="card-body">
                    <h2 class="card-title text-2xl mb-6 flex items-center gap-3">
                        <span class="text-4xl">⚫</span>
                        <div>
                            <div>Time Preto</div>
                            <div class="text-sm font-normal opacity-75">
                                {{ $divisao['time_preto']->count() }} jogadores
                            </div>
                        </div>
                    </h2>

                    <div class="space-y-3">
                        @foreach ($divisao['time_preto'] as $index => $atleta)
                            <div
                                class="flex items-center gap-4 p-3 bg-neutral-focus rounded-lg hover:bg-opacity-80 transition-colors">
                                <div class="avatar placeholder">
                                    <div class="bg-neutral-content text-neutral rounded-full w-12">
                                        <span class="text-xl font-bold">{{ $index + 1 }}</span>
                                    </div>
                                </div>

                                <div class="flex-1">
                                    <p class="font-semibold text-lg">
                                        {{ $atleta->apelido ?: $atleta->nome }}
                                    </p>
                                    <div class="flex items-center gap-2 mt-1">
                                        <span
                                            class="badge badge-outline badge-sm text-neutral-content border-neutral-content">
                                            {{ $atleta->posicao_formatada }}
                                        </span>
                                        <span class="text-sm opacity-75">
                                            {{ str_repeat('⭐', $atleta->nivel_habilidade) }}
                                        </span>
                                    </div>
                                </div>

                                <div class="text-right">
                                    <div class="text-2xl font-bold">{{ $atleta->nivel_habilidade }}</div>
                                    <div class="text-xs opacity-75">Nível</div>
                                </div>
                            </div>
                        @endforeach
                    </div>

                    <!-- Resumo do Time -->
                    <div class="divider"></div>
                    <div class="stats stats-vertical bg-neutral-focus shadow">
                        <div class="stat p-4">
                            <div class="stat-title text-neutral-content opacity-75">Total de Habilidade</div>
                            <div class="stat-value text-neutral-content">{{ $divisao['estatisticas']['preto']['soma'] }}
                            </div>
                            <div class="stat-desc text-neutral-content opacity-75">
                                Média: {{ $divisao['estatisticas']['preto']['media'] }} por jogador
                            </div>
                        </div>
                    </div>
                </div>
            </div>

            <!-- Time Branco -->
            <div class="card bg-base-100 border-4 border-base-300 shadow-2xl">
                <div class="card-body">
                    <h2 class="card-title text-2xl mb-6 flex items-center gap-3">
                        <span class="text-4xl">⚪</span>
                        <div>
                            <div>Time Branco</div>
                            <div class="text-sm font-normal text-gray-500">
                                {{ $divisao['time_branco']->count() }} jogadores
                            </div>
                        </div>
                    </h2>

                    <div class="space-y-3">
                        @foreach ($divisao['time_branco'] as $index => $atleta)
                            <div
                                class="flex items-center gap-4 p-3 bg-base-200 rounded-lg hover:bg-base-300 transition-colors">
                                <div class="avatar placeholder">
                                    <div class="bg-primary text-primary-content rounded-full w-12">
                                        <span class="text-xl font-bold">{{ $index + 1 }}</span>
                                    </div>
                                </div>

                                <div class="flex-1">
                                    <p class="font-semibold text-lg">
                                        {{ $atleta->apelido ?: $atleta->nome }}
                                    </p>
                                    <div class="flex items-center gap-2 mt-1">
                                        <span class="badge badge-outline badge-sm">
                                            {{ $atleta->posicao_formatada }}
                                        </span>
                                        <span class="text-sm text-gray-500">
                                            {{ str_repeat('⭐', $atleta->nivel_habilidade) }}
                                        </span>
                                    </div>
                                </div>

                                <div class="text-right">
                                    <div class="text-2xl font-bold text-primary">{{ $atleta->nivel_habilidade }}</div>
                                    <div class="text-xs text-gray-500">Nível</div>
                                </div>
                            </div>
                        @endforeach
                    </div>

                    <!-- Resumo do Time -->
                    <div class="divider"></div>
                    <div class="stats stats-vertical bg-base-200 shadow">
                        <div class="stat p-4">
                            <div class="stat-title">Total de Habilidade</div>
                            <div class="stat-value text-primary">{{ $divisao['estatisticas']['branco']['soma'] }}</div>
                            <div class="stat-desc">
                                Média: {{ $divisao['estatisticas']['branco']['media'] }} por jogador
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <!-- Comparação Visual -->
        <div class="card bg-base-100 shadow-lg">
            <div class="card-body">
                <h3 class="card-title mb-4">📊 Análise Comparativa</h3>

                <div class="space-y-4">
                    <!-- Comparação de Jogadores -->
                    <div>
                        <div class="flex items-center justify-between mb-2">
                            <span class="text-sm font-medium">Número de Jogadores</span>
                            <span class="text-sm text-gray-500">
                                Diferença: {{ abs($divisao['time_preto']->count() - $divisao['time_branco']->count()) }}
                            </span>
                        </div>
                        <div class="grid grid-cols-2 gap-2">
                            <div>
                                <div
                                    class="h-12 bg-neutral rounded flex items-center justify-center text-neutral-content font-bold">
                                    {{ $divisao['time_preto']->count() }}
                                </div>
                            </div>
                            <div>
                                <div class="h-12 bg-base-200 border-2 rounded flex items-center justify-center font-bold">
                                    {{ $divisao['time_branco']->count() }}
                                </div>
                            </div>
                        </div>
                    </div>

                    <!-- Comparação de Habilidade Total -->
                    <div>
                        <div class="flex items-center justify-between mb-2">
                            <span class="text-sm font-medium">Habilidade Total</span>
                            <span class="text-sm text-gray-500">
                                Diferença: {{ $divisao['estatisticas']['diferenca_soma'] }}
                            </span>
                        </div>
                        <div class="grid grid-cols-2 gap-2">
                            <div>
                                <progress class="progress progress-neutral w-full"
                                    value="{{ $divisao['estatisticas']['preto']['soma'] }}"
                                    max="{{ max($divisao['estatisticas']['preto']['soma'], $divisao['estatisticas']['branco']['soma']) }}"></progress>
                                <p class="text-center text-sm mt-1">{{ $divisao['estatisticas']['preto']['soma'] }}</p>
                            </div>
                            <div>
                                <progress class="progress progress-primary w-full"
                                    value="{{ $divisao['estatisticas']['branco']['soma'] }}"
                                    max="{{ max($divisao['estatisticas']['preto']['soma'], $divisao['estatisticas']['branco']['soma']) }}"></progress>
                                <p class="text-center text-sm mt-1">{{ $divisao['estatisticas']['branco']['soma'] }}</p>
                            </div>
                        </div>
                    </div>

                    <!-- Comparação de Média -->
                    <div>
                        <div class="flex items-center justify-between mb-2">
                            <span class="text-sm font-medium">Habilidade Média</span>
                            <span class="text-sm text-gray-500">
                                Diferença: {{ number_format($divisao['estatisticas']['diferenca_media'], 2) }}
                            </span>
                        </div>
                        <div class="grid grid-cols-2 gap-2">
                            <div>
                                <progress class="progress progress-neutral w-full"
                                    value="{{ $divisao['estatisticas']['preto']['media'] }}" max="5"></progress>
                                <p class="text-center text-sm mt-1">{{ $divisao['estatisticas']['preto']['media'] }}</p>
                            </div>
                            <div>
                                <progress class="progress progress-primary w-full"
                                    value="{{ $divisao['estatisticas']['branco']['media'] }}" max="5"></progress>
                                <p class="text-center text-sm mt-1">{{ $divisao['estatisticas']['branco']['media'] }}</p>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <!-- Ações -->
        <div class="card bg-base-100 shadow-lg">
            <div class="card-body">
                <div class="flex flex-col sm:flex-row gap-4">
                    <form method="POST" action="{{ route('partidas.divisao.confirmar', $partida) }}" class="flex-1">
                        @csrf
                        <button type="submit" class="btn btn-success btn-lg w-full">
                            <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7">
                                </path>
                            </svg>
                            Confirmar Divisão
                        </button>
                    </form>

                    <a href="{{ route('partidas.divisao.preview', $partida) }}" class="btn btn-warning btn-lg flex-1">
                        <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                d="M4 4v5h.582m15.356 2A8.001 8.001 0 004.582 9m0 0H9m11 11v-5h-.581m0 0a8.003 8.003 0 01-15.357-2m15.357 2H15" />
                        </svg>
                        Gerar Nova Divisão
                    </a>

                    <a href="{{ route('partidas.show', $partida) }}" class="btn btn-ghost btn-lg flex-1">
                        Cancelar
                    </a>
                </div>
            </div>
        </div>
    </div>
@endsection
