@extends('layouts.app')

@section('title', 'Dashboard - N.O.C FUTSAL')

@section('header')
    <div>
        <h1 class="text-3xl font-bold text-base-content">Dashboard</h1>
        <p class="mt-1 text-base-content">Visão geral do N.O.C. Futsal</p>
    </div>
@endsection

@section('content')
    <!-- Stats Cards -->
    <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-4 gap-6 mb-8">
        <div class="stat bg-primary text-primary-content rounded-xl shadow-lg">
            <div class="stat-figure text-primary-content opacity-60">
                <svg class="inline-block w-8 h-8 stroke-current" fill="none" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                        d="M17 20h5v-2a3 3 0 00-5.356-1.857M17 20H7m10 0v-2c0-.656-.126-1.283-.356-1.857M7 20H2v-2a3 3 0 015.356-1.857M7 20v-2c0-.656.126-1.283.356-1.857m0 0a5.002 5.002 0 019.288 0M15 7a3 3 0 11-6 0 3 3 0 016 0zm6 3a2 2 0 11-4 0 2 2 0 014 0zM7 10a2 2 0 11-4 0 2 2 0 014 0z" />
                </svg>
            </div>
            <div class="stat-title text-primary-content opacity-90">Total de Atletas</div>
            <div class="stat-value">{{ $totalAtletas }}</div>
            <div class="stat-desc text-primary-content opacity-75">Cadastrados no sistema</div>
        </div>

        <div class="stat bg-success text-success-content rounded-xl shadow-lg">
            <div class="stat-figure text-success-content opacity-60">
                <svg class="inline-block w-8 h-8 stroke-current" fill="none" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                        d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z" />
                </svg>
            </div>
            <div class="stat-title text-success-content opacity-90">Ativos</div>
            <div class="stat-value">{{ $atletasAtivos }}</div>
            <div class="stat-desc text-success-content opacity-75">
                {{ $totalAtletas > 0 ? number_format(($atletasAtivos / $totalAtletas) * 100, 1) : 0 }}% do total
            </div>
        </div>

        <div class="stat bg-warning text-warning-content rounded-xl shadow-lg">
            <div class="stat-figure text-warning-content opacity-60">
                <svg class="inline-block w-8 h-8 stroke-current" fill="none" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                        d="M12 9v2m0 4h.01m-6.938 4h13.856c1.54 0 2.502-1.667 1.732-2.5L13.732 4c-.77-.833-1.964-.833-2.732 0L4.082 14.5c-.77.833.192 2.5 1.732 2.5z" />
                </svg>
            </div>
            <div class="stat-title text-warning-content opacity-90">Inativos</div>
            <div class="stat-value">{{ $atletasInativos }}</div>
            <div class="stat-desc text-warning-content opacity-75">Temporariamente fora</div>
        </div>

        <div class="stat bg-info text-info-content rounded-xl shadow-lg">
            <div class="stat-figure text-info-content opacity-60">
                <div class="text-2xl">⭐</div>
            </div>
            <div class="stat-title text-info-content opacity-90">Nível Médio</div>
            <div class="stat-value">{{ $nivelMedio ? number_format($nivelMedio, 1) : '0.0' }}</div>
            <div class="stat-desc text-info-content opacity-75">Habilidade do time</div>
        </div>
    </div>

    <div class="grid grid-cols-1 lg:grid-cols-3 gap-8">
        <!-- Gráficos e Estatísticas -->
        <div class="lg:col-span-2 space-y-8">
            <!-- Distribuição por Posição -->
            <div class="card bg-base-100 shadow-xl">
                <div class="card-body">
                    <h2 class="card-title text-xl mb-6">
                        <span class="text-2xl">📊</span>
                        Distribuição por Posição
                    </h2>

                    @if (!empty($distribuicaoPosicoes))
                        <div class="space-y-4">
                            @foreach ($distribuicaoPosicoes as $posicao => $total)
                                @php
                                    $posicaoLabel = \App\Models\Atleta::POSICOES[$posicao] ?? $posicao;
                                    $porcentagem = $atletasAtivos > 0 ? ($total / $atletasAtivos) * 100 : 0;
                                @endphp
                                <div class="flex items-center justify-between">
                                    <div class="flex items-center gap-3">
                                        <span class="badge badge-outline">{{ $posicaoLabel }}</span>
                                        <span class="text-sm text-gray-600">{{ $total }} atleta(s)</span>
                                    </div>
                                    <div class="flex items-center gap-2">
                                        <progress class="progress progress-primary w-20" value="{{ $porcentagem }}"
                                            max="100"></progress>
                                        <span class="text-sm font-medium">{{ number_format($porcentagem, 1) }}%</span>
                                    </div>
                                </div>
                            @endforeach
                        </div>
                    @else
                        <div class="text-center py-8 text-gray-500">
                            <div class="text-4xl mb-2">📋</div>
                            <p>Nenhum atleta ativo cadastrado</p>
                        </div>
                    @endif
                </div>
            </div>

            <!-- Distribuição por Nível -->
            <div class="card bg-base-100 shadow-xl">
                <div class="card-body">
                    <h2 class="card-title text-xl mb-6">
                        <span class="text-2xl">⭐</span>
                        Distribuição por Nível de Habilidade
                    </h2>

                    @if (!empty($distribuicaoNiveis))
                        <div class="space-y-4">
                            @for ($nivel = 1; $nivel <= 5; $nivel++)
                                @php
                                    $total = $distribuicaoNiveis[$nivel] ?? 0;
                                    $porcentagem = $atletasAtivos > 0 ? ($total / $atletasAtivos) * 100 : 0;
                                    $nivelLabel = \App\Models\Atleta::NIVEIS_HABILIDADE[$nivel];
                                @endphp
                                <div class="flex items-center justify-between">
                                    <div class="flex items-center gap-3">
                                        <div class="flex">
                                            @for ($i = 1; $i <= 5; $i++)
                                                <span
                                                    class="text-sm {{ $i <= $nivel ? 'text-yellow-400' : 'text-gray-300' }}">★</span>
                                            @endfor
                                        </div>
                                        <span class="text-sm">{{ $nivelLabel }}</span>
                                        <span class="text-xs text-gray-500">({{ $total }})</span>
                                    </div>
                                    <div class="flex items-center gap-2">
                                        <progress class="progress progress-warning w-20" value="{{ $porcentagem }}"
                                            max="100"></progress>
                                        <span class="text-sm font-medium">{{ number_format($porcentagem, 1) }}%</span>
                                    </div>
                                </div>
                            @endfor
                        </div>
                    @else
                        <div class="text-center py-8 text-gray-500">
                            <div class="text-4xl mb-2">⭐</div>
                            <p>Nenhum atleta ativo cadastrado</p>
                        </div>
                    @endif
                </div>
            </div>
        </div>

        <!-- Sidebar -->
        <div class="space-y-6">
            <!-- Ações Rápidas -->
            <div class="card bg-base-100 shadow-xl">
                <div class="card-body">
                    <h3 class="card-title text-lg mb-4">
                        ⚡ Ações Rápidas
                    </h3>

                    <div class="space-y-3">
                        <a href="{{ route('atletas.create') }}" class="btn btn-primary w-full">
                            <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4v16m8-8H4">
                                </path>
                            </svg>
                            Novo Atleta
                        </a>

                        <a href="{{ route('atletas.index') }}" class="btn btn-outline w-full">
                            <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                    d="M17 20h5v-2a3 3 0 00-5.356-1.857M17 20H7m10 0v-2c0-.656-.126-1.283-.356-1.857M7 20H2v-2a3 3 0 015.356-1.857M7 20v-2c0-.656.126-1.283.356-1.857m0 0a5.002 5.002 0 019.288 0M15 7a3 3 0 11-6 0 3 3 0 016 0zm6 3a2 2 0 11-4 0 2 2 0 014 0zM7 10a2 2 0 11-4 0 2 2 0 014 0z" />
                            </svg>
                            Ver Todos os Atletas
                        </a>

                        <button class="btn btn-outline w-full" disabled>
                            <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                    d="M8 7V3a4 4 0 118 0v4"></path>
                                <path d="M4 7h16a2 2 0 012 2v10a2 2 0 01-2 2H4a2 2 0 01-2-2V9a2 2 0 012-2z"></path>
                            </svg>
                            Nova Partida
                        </button>
                    </div>

                    <div class="text-xs text-gray-500 mt-3">
                        Mais recursos serão adicionados em versões futuras
                    </div>
                </div>
            </div>

            <!-- Últimos Atletas -->
            <div class="card bg-base-100 shadow-xl">
                <div class="card-body">
                    <h3 class="card-title text-lg mb-4">
                        🕐 Últimos Cadastros
                    </h3>

                    @if ($ultimosAtletas->count() > 0)
                        <div class="space-y-3">
                            @foreach ($ultimosAtletas as $atleta)
                                <div
                                    class="flex items-center justify-between p-2 hover:bg-base-200 rounded-lg transition-colors">
                                    <div class="flex items-center gap-3">
                                        <div class="avatar placeholder">
                                            <div class="bg-primary text-primary-content rounded-full w-8">
                                                <span class="text-xs">{{ substr($atleta->nome, 0, 1) }}</span>
                                            </div>
                                        </div>
                                        <div>
                                            <p class="font-medium text-sm">{{ $atleta->nome }}</p>
                                            <p class="text-xs text-gray-500">{{ $atleta->posicao_formatada }}</p>
                                        </div>
                                    </div>
                                    <div class="text-right">
                                        <span
                                            class="badge {{ $atleta->isAtivo() ? 'badge-success' : 'badge-warning' }} badge-xs">
                                            {{ $atleta->status_formatado }}
                                        </span>
                                        <p class="text-xs text-gray-500 mt-1">
                                            {{ $atleta->created_at->diffForHumans() }}
                                        </p>
                                    </div>
                                </div>
                            @endforeach
                        </div>

                        <div class="text-center mt-4">
                            <a href="{{ route('atletas.index') }}" class="link link-primary text-sm">
                                Ver todos os atletas →
                            </a>
                        </div>
                    @else
                        <div class="text-center py-8 text-gray-500">
                            <div class="text-4xl mb-2">👥</div>
                            <p class="text-sm">Nenhum atleta cadastrado ainda</p>
                            <a href="{{ route('atletas.create') }}" class="link link-primary text-sm mt-2 block">
                                Cadastrar primeiro atleta
                            </a>
                        </div>
                    @endif
                </div>
            </div>

            <!-- Próximas Features -->
            <div class="card bg-base-100 shadow-xl">
                <div class="card-body">
                    <h3 class="card-title text-lg mb-4">
                        🚀 Próximas Features
                    </h3>

                    <div class="space-y-3">
                        <div class="flex items-center gap-3 opacity-60">
                            <div class="w-2 h-2 bg-gray-400 rounded-full"></div>
                            <span class="text-sm">Sistema de Partidas</span>
                        </div>
                        <div class="flex items-center gap-3 opacity-60">
                            <div class="w-2 h-2 bg-gray-400 rounded-full"></div>
                            <span class="text-sm">Balanceamento de Times</span>
                        </div>
                        <div class="flex items-center gap-3 opacity-60">
                            <div class="w-2 h-2 bg-gray-400 rounded-full"></div>
                            <span class="text-sm">Gestão Financeira</span>
                        </div>
                        <div class="flex items-center gap-3 opacity-60">
                            <div class="w-2 h-2 bg-gray-400 rounded-full"></div>
                            <span class="text-sm">Organização de Churrascos</span>
                        </div>
                        <div class="flex items-center gap-3 opacity-60">
                            <div class="w-2 h-2 bg-gray-400 rounded-full"></div>
                            <span class="text-sm">Estatísticas Avançadas</span>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
@endsection
