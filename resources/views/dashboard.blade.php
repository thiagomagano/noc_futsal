@extends('layouts.app')

@section('title', 'Dashboard - N.O.C FUTSAL')

@section('header')
    <div>
        <h1 class="text-3xl font-bold text-base-content">Dashboard</h1>
        <p class="mt-1 text-base-content">Visão geral do N.O.C. Futsal</p>
    </div>
@endsection

@section('content')

    <div class="space-y-6 grid grid-cols-1 lg:grid-cols-3 gap-8">
        <!-- Ações Rápidas -->
        <div class="card bg-base-100 shadow-xl">
            <div class="card-body">
                <h3 class="card-title text-lg mb-4">
                    ⚡ Ações Rápidas
                </h3>
                <a href="{{ route('partidas.create') }}" class="btn btn-primary w-full">
                    <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4v16m8-8H4">
                        </path>
                    </svg>
                    Nova Partida
                </a>

                <div class="space-y-3">
                    <a href="{{ route('atletas.create') }}" class="btn btn-secondary w-full">
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
                    👥 Últimos Jogadores
                </h3>

                @if ($ultimosAtletas->count() > 0)
                    <div class="space-y-3">
                        @foreach ($ultimosAtletas as $atleta)
                            <a href={{ route('atletas.show', $atleta->id) }}
                                class="flex items-center justify-between p-2 hover:bg-base-200 rounded-lg transition-colors">
                                <div class="flex items-center gap-3">
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
                            </a>
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


        <!-- Últimos Partidas -->
        <div class="card bg-base-100 shadow-xl">
            <div class="card-body">
                <h3 class="card-title text-lg mb-4">
                    🕐 Últimas Partidas
                </h3>

                @if ($ultimasPartidas->count() > 0)
                    <div class="space-y-3">
                        @foreach ($ultimasPartidas as $partida)
                            <a href="{{ route('partidas.show', $partida->id) }}"
                                class="flex items-center justify-between p-2 hover:bg-base-200 rounded-lg transition-colors">

                                <div class="flex items-center gap-3">
                                    <div>
                                        <p class="font-medium text-sm">{{ $partida->data_formatada }}</p>
                                        <p class="text-xs text-gray-500">{{ $partida->local }}</p>
                                    </div>
                                </div>

                                <div class="text-right">
                                    <span class="badge {{ $atleta->status ? 'badge-success' : 'badge-warning' }} badge-xs">
                                        {{ $partida->status_formatado }}
                                    </span>
                                    <p class="text-xs text-gray-500 mt-1">
                                        {{ $partida->created_at->diffForHumans() }}
                                    </p>
                                </div>
                            </a>
                        @endforeach
                    </div>

                    <div class="text-center mt-4">
                        <a href="{{ route('partidas.index') }}" class="link link-primary text-sm">
                            Ver todas partidas →
                        </a>
                    </div>
                @else
                    <div class="text-center py-8 text-gray-500">
                        <div class="text-4xl mb-2">⚽</div>
                        <p class="text-sm">Nenhuma partida cadastrada ainda</p>
                        <a href="{{ route('partidas.create') }}" class="link link-primary text-sm mt-2 block">
                            Cadastrar primeira partida
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



@endsection
