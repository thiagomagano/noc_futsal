@extends('layouts.app')

@section('title', $atleta->nome . ' - Futsal Manager')

@section('breadcrumb')
    <li><a href="{{ route('dashboard') }}">Dashboard</a></li>
    <li><a href="{{ route('atletas.index') }}">Atletas</a></li>
    <li>{{ $atleta->nome }}</li>
@endsection

@section('header')
    <div class="flex flex-col lg:flex-row lg:items-center lg:justify-between gap-4">
        <div>
            <h1 class="text-3xl font-bold text-base-content">{{ $atleta->nome_completo }}</h1>
            <div class="flex items-center gap-4 mt-2">
                <span class="badge badge-outline badge-lg">{{ $atleta->posicao_formatada }}</span>
                <span class="badge {{ $atleta->isAtivo() ? 'badge-success' : 'badge-warning' }} badge-lg">
                    {{ $atleta->status_formatado }}
                </span>
            </div>
        </div>
        <div class="flex gap-2">
            <a href="{{ route('atletas.edit', $atleta) }}" class="btn btn-primary">
                <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                        d="M11 5H6a2 2 0 00-2 2v11a2 2 0 002 2h11a2 2 0 002-2v-5m-1.414-9.414a2 2 0 112.828 2.828L11.828 15H9v-2.828l8.586-8.586z">
                    </path>
                </svg>
                Editar
            </a>
            <div class="dropdown dropdown-end">
                <div tabindex="0" role="button" class="btn btn-ghost">
                    <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                            d="M12 5v.01M12 12v.01M12 19v.01M12 6a1 1 0 110-2 1 1 0 010 2zm0 7a1 1 0 110-2 1 1 0 010 2zm0 7a1 1 0 110-2 1 1 0 010 2z">
                        </path>
                    </svg>
                </div>
                <ul tabindex="0" class="dropdown-content z-[1] menu p-2 shadow bg-base-100 rounded-box w-52">
                    <li>
                        <form action="{{ route('atletas.toggle-status', $atleta) }}" method="POST" class="inline">
                            @csrf
                            @method('PATCH')
                            <button type="submit" class="w-full text-left">
                                {{ $atleta->isAtivo() ? '⏸️ Inativar' : '▶️ Ativar' }}
                            </button>
                        </form>
                    </li>
                    <div class="divider my-0"></div>
                    <li>
                        <form action="{{ route('atletas.destroy', $atleta) }}" method="POST"
                            onsubmit="return confirm('Tem certeza que deseja remover este atleta?')" class="inline">
                            @csrf
                            @method('DELETE')
                            <button type="submit" class="w-full text-left text-error">
                                🗑️ Remover
                            </button>
                        </form>
                    </li>
                </ul>
            </div>
        </div>
    </div>
@endsection

@section('content')
    <div class="max-w-4xl mx-auto">
        <div class="grid grid-cols-1 lg:grid-cols-3 gap-6">
            <!-- Informações Principais -->
            <div class="lg:col-span-2 space-y-6">
                <!-- Card Principal -->
                <div class="card bg-base-100 shadow-lg">
                    <div class="card-body">
                        <h2 class="card-title text-xl mb-4">
                            <span class="text-2xl">👤</span>
                            Informações Pessoais
                        </h2>

                        <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                            <div class="space-y-4">
                                <div>
                                    <label class="text-sm font-medium text-gray-500">Nome Completo</label>
                                    <p class="text-lg font-semibold">{{ $atleta->nome }}</p>
                                </div>

                                @if ($atleta->apelido)
                                    <div>
                                        <label class="text-sm font-medium text-gray-500">Apelido</label>
                                        <p class="text-lg">"{{ $atleta->apelido }}"</p>
                                    </div>
                                @endif

                                <div>
                                    <label class="text-sm font-medium text-gray-500">Telefone</label>
                                    <div class="flex items-center gap-2">
                                        <p class="text-lg">{{ $atleta->telefone }}</p>
                                        <a href="tel:{{ $atleta->telefone }}" class="btn btn-ghost btn-xs">
                                            📞
                                        </a>
                                        <a href="https://wa.me/55{{ preg_replace('/[^0-9]/', '', $atleta->telefone) }}"
                                            target="_blank" class="btn btn-ghost btn-xs">
                                            💬
                                        </a>
                                    </div>
                                </div>
                            </div>

                            <div class="space-y-4">
                                <div>
                                    <label class="text-sm font-medium text-gray-500">Posição Preferida</label>
                                    <p class="text-lg">{{ $atleta->posicao_formatada }}</p>
                                </div>

                                <div>
                                    <label class="text-sm font-medium text-gray-500">Status</label>
                                    <p class="text-lg">
                                        <span
                                            class="badge {{ $atleta->isAtivo() ? 'badge-success' : 'badge-warning' }} badge-lg">
                                            {{ $atleta->status_formatado }}
                                        </span>
                                    </p>
                                </div>

                                <div>
                                    <label class="text-sm font-medium text-gray-500">Nível de Habilidade</label>
                                    <div class="flex items-center gap-2">
                                        <x-star-rating :value="$atleta->nivel_habilidade" readonly />
                                    </div>
                                </div>
                            </div>
                        </div>

                        @if ($atleta->observacoes)
                            <div class="divider"></div>
                            <div>
                                <label class="text-sm font-medium text-gray-500">Observações</label>
                                <div class="bg-base-200 p-4 rounded-lg mt-2">
                                    <p class="whitespace-pre-wrap">{{ $atleta->observacoes }}</p>
                                </div>
                            </div>
                        @endif
                    </div>
                </div>

                <!-- Estatísticas Futuras -->
                <div class="card bg-base-100 shadow-lg">
                    <div class="card-body">
                        <h2 class="card-title text-xl mb-4">
                            <span class="text-2xl">📊</span>
                            Estatísticas
                        </h2>

                        <div class="grid grid-cols-1 md:grid-cols-3 gap-4">
                            <div class="stat bg-base-200 rounded-lg">
                                <div class="stat-title">Jogos</div>
                                <div class="stat-value text-sm">Em breve</div>
                                <div class="stat-desc">Total de partidas</div>
                            </div>

                            <div class="stat bg-base-200 rounded-lg">
                                <div class="stat-title">Gols</div>
                                <div class="stat-value text-sm">Em breve</div>
                                <div class="stat-desc">Total de gols</div>
                            </div>

                            <div class="stat bg-base-200 rounded-lg">
                                <div class="stat-title">Vitórias</div>
                                <div class="stat-value text-sm">Em breve</div>
                                <div class="stat-desc">Percentual</div>
                            </div>
                        </div>

                        <div class="alert alert-info mt-4">
                            <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24"
                                class="stroke-current shrink-0 w-6 h-6">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                    d="M13 16h-1v-4h-1m1-4h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z"></path>
                            </svg>
                            <span>As estatísticas serão exibidas quando o sistema de partidas for implementado.</span>
                        </div>
                    </div>
                </div>
            </div>

            <!-- Sidebar -->
            <div class="space-y-6">
                <!-- Quick Actions -->
                <div class="card bg-base-100 shadow-lg">
                    <div class="card-body">
                        <h3 class="card-title text-lg mb-4">
                            ⚡ Ações Rápidas
                        </h3>

                        <div class="space-y-2">
                            <button class="btn btn-outline btn-sm w-full justify-start" disabled>
                                📋 Ver Histórico
                            </button>
                            <button class="btn btn-outline btn-sm w-full justify-start" disabled>
                                📈 Comparar Desempenho
                            </button>
                            <button class="btn btn-outline btn-sm w-full justify-start" disabled>
                                🏆 Ver Conquistas
                            </button>
                        </div>

                        <div class="text-xs text-gray-500 mt-2">
                            Recursos disponíveis em versões futuras
                        </div>
                    </div>
                </div>

                <!-- Informações do Sistema -->
                <div class="card bg-base-100 shadow-lg">
                    <div class="card-body">
                        <h3 class="card-title text-lg mb-4">
                            🔧 Sistema
                        </h3>

                        <div class="space-y-3">
                            <div>
                                <label class="text-xs font-medium text-gray-500 uppercase tracking-wider">
                                    Cadastrado em
                                </label>
                                <p class="text-sm mt-1">
                                    {{ $atleta->created_at->format('d/m/Y') }}
                                    <br>
                                    <span class="text-gray-500">{{ $atleta->created_at->format('H:i') }}</span>
                                </p>
                            </div>

                            <div>
                                <label class="text-xs font-medium text-gray-500 uppercase tracking-wider">
                                    Última atualização
                                </label>
                                <p class="text-sm mt-1">
                                    {{ $atleta->updated_at->format('d/m/Y') }}
                                    <br>
                                    <span class="text-gray-500">{{ $atleta->updated_at->format('H:i') }}</span>
                                </p>
                            </div>

                            <div>
                                <label class="text-xs font-medium text-gray-500 uppercase tracking-wider">
                                    ID do Atleta
                                </label>
                                <p class="text-sm mt-1 font-mono">#{{ $atleta->id }}</p>
                            </div>
                        </div>
                    </div>
                </div>

                <!-- Navegação -->
                <div class="card bg-base-100 shadow-lg">
                    <div class="card-body">
                        <h3 class="card-title text-lg mb-4">
                            🧭 Navegação
                        </h3>

                        <div class="space-y-2">
                            <a href="{{ route('atletas.index') }}" class="btn btn-ghost btn-sm w-full justify-start">
                                ← Voltar à Lista
                            </a>
                            <a href="{{ route('atletas.edit', $atleta) }}"
                                class="btn btn-ghost btn-sm w-full justify-start">
                                ✏️ Editar Atleta
                            </a>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
@endsection
