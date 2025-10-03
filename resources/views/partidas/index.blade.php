@extends('layouts.app')

@section('title', 'Partidas - Futsal Manager')

@section('breadcrumb')
    <li><a href="{{ route('dashboard') }}">Dashboard</a></li>
    <li>Partidas</li>
@endsection

@section('header')
    <div class="flex flex-col lg:flex-row lg:items-center lg:justify-between gap-4">
        <div>
            <h1 class="text-3xl font-bold text-gray-200">Partidas</h1>
            <p class="text-gray-400 mt-1">Organize e gerencie as partidas do time</p>
        </div>
        <div class="flex gap-2">
            <a href="{{ route('partidas.create') }}" class="btn btn-primary">
                <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4v16m8-8H4"></path>
                </svg>
                Nova Partida
            </a>
        </div>
    </div>
@endsection

@section('content')
    <!-- Stats Cards -->
    <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-4 gap-4 mb-6">
        <div class="stat bg-base-200 rounded-lg">
            <div class="stat-figure text-primary">
                <svg class="inline-block w-8 h-8 stroke-current" fill="none" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                        d="M8 7V3m8 4V3m-9 8h10M5 21h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v12a2 2 0 002 2z" />
                </svg>
            </div>
            <div class="stat-title">Total de Partidas</div>
            <div class="stat-value">{{ $partidas->total() }}</div>
            <div class="stat-desc">Cadastradas no sistema</div>
        </div>

        <div class="stat bg-base-200 rounded-lg">
            <div class="stat-figure text-info">
                <svg class="inline-block w-8 h-8 stroke-current" fill="none" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                        d="M12 8v4l3 3m6-3a9 9 0 11-18 0 9 9 0 0118 0z" />
                </svg>
            </div>
            <div class="stat-title">Abertas</div>
            <div class="stat-value text-info">{{ $partidas->where('status', 'aberta')->count() }}</div>
            <div class="stat-desc">Aguardando confirmações</div>
        </div>

        <div class="stat bg-base-200 rounded-lg">
            <div class="stat-figure text-success">
                <svg class="inline-block w-8 h-8 stroke-current" fill="none" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                        d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z" />
                </svg>
            </div>
            <div class="stat-title">Confirmadas</div>
            <div class="stat-value text-success">
                {{ $partidas->whereIn('status', ['confirmada', 'times_definidos'])->count() }}</div>
            <div class="stat-desc">Com quórum</div>
        </div>

        <div class="stat bg-base-200 rounded-lg">
            <div class="stat-figure text-warning">
                <svg class="inline-block w-8 h-8 stroke-current" fill="none" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                        d="M5 3v4M3 5h4M6 17v4m-2-2h4m5-16l2.286 6.857L21 12l-5.714 2.143L13 21l-2.286-6.857L5 12l5.714-2.143L13 3z" />
                </svg>
            </div>
            <div class="stat-title">Finalizadas</div>
            <div class="stat-value text-warning">{{ $partidas->where('status', 'finalizada')->count() }}</div>
            <div class="stat-desc">Histórico</div>
        </div>
    </div>

    <!-- Filtros -->
    <div class="card bg-base-100 shadow-sm mb-6">
        <div class="card-body">
            <form method="GET" action="{{ route('partidas.index') }}" class="space-y-4">
                <div class="grid grid-cols-1 md:grid-cols-3 gap-4">
                    <!-- Status -->
                    <div class="form-control">
                        <label class="label">
                            <span class="label-text">Status</span>
                        </label>
                        <select name="status" class="select select-bordered select-sm">
                            <option value="">Todos</option>
                            @foreach ($filtros['status_options'] as $key => $label)
                                <option value="{{ $key }}" {{ request('status') === $key ? 'selected' : '' }}>
                                    {{ $label }}
                                </option>
                            @endforeach
                        </select>
                    </div>

                    <!-- Tipo (Próximas/Passadas) -->
                    <div class="form-control">
                        <label class="label">
                            <span class="label-text">Período</span>
                        </label>
                        <select name="tipo" class="select select-bordered select-sm">
                            <option value="">Todas</option>
                            <option value="proximas" {{ request('tipo') === 'proximas' ? 'selected' : '' }}>
                                Próximas
                            </option>
                            <option value="passadas" {{ request('tipo') === 'passadas' ? 'selected' : '' }}>
                                Passadas
                            </option>
                        </select>
                    </div>

                    <!-- Ações -->
                    <div class="form-control">
                        <label class="label">
                            <span class="label-text">&nbsp;</span>
                        </label>
                        <div class="flex gap-2">
                            <button type="submit" class="btn btn-primary btn-sm flex-1">
                                Filtrar
                            </button>
                            <a href="{{ route('partidas.index') }}" class="btn btn-ghost btn-sm">
                                Limpar
                            </a>
                        </div>
                    </div>
                </div>
            </form>
        </div>
    </div>

    <!-- Lista de Partidas -->
    @if ($partidas->count() > 0)
        <div class="grid grid-cols-1 lg:grid-cols-2 gap-6">
            @foreach ($partidas as $partida)
                <div class="card bg-base-100 shadow-lg hover:shadow-xl transition-shadow">
                    <div class="card-body">
                        <!-- Header do Card -->
                        <div class="flex items-start justify-between mb-4">
                            <div class="flex-1">
                                <div class="flex items-center gap-2 mb-2">
                                    <h3 class="card-title text-lg">
                                        {{ $partida->data_formatada }}
                                    </h3>
                                    <span
                                        class="badge 
                                        @if ($partida->isAberta()) badge-info
                                        @elseif($partida->isConfirmada()) badge-success
                                        @elseif($partida->temTimesDefinidos()) badge-primary
                                        @elseif($partida->isFinalizada()) badge-neutral
                                        @else badge-warning @endif
                                    ">
                                        {{ $partida->status_formatado }}
                                    </span>
                                </div>

                                <div class="flex items-center gap-2 text-sm text-gray-400">
                                    <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                            d="M12 8v4l3 3m6-3a9 9 0 11-18 0 9 9 0 0118 0z" />
                                    </svg>
                                    <span>{{ $partida->hora_formatada }}</span>
                                </div>

                                <div class="flex items-center gap-2 text-sm text-gray-400 mt-1">
                                    <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                            d="M17.657 16.657L13.414 20.9a1.998 1.998 0 01-2.827 0l-4.244-4.243a8 8 0 1111.314 0z" />
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                            d="M15 11a3 3 0 11-6 0 3 3 0 016 0z" />
                                    </svg>
                                    <span>{{ $partida->local }}</span>
                                </div>
                            </div>
                        </div>

                        <!-- Info de Jogadores -->
                        <div class="divider my-2"></div>

                        <div class="grid grid-cols-2 gap-4">
                            <div>
                                <p class="text-xs text-gray-500 mb-1">Total de Atletas</p>
                                <p class="text-2xl font-bold">{{ $partida->atletas->count() }}</p>
                            </div>
                            <div>
                                <p class="text-xs text-gray-500 mb-1">Confirmados</p>
                                <p class="text-2xl font-bold {{ $partida->temQuorum() ? 'text-success' : '' }}">
                                    {{ $partida->getTotalConfirmados() }}
                                </p>
                            </div>
                        </div>

                        @if ($partida->getTotalConfirmados() > 0)
                            <div class="mt-2">
                                <progress
                                    class="progress {{ $partida->temQuorum() ? 'progress-success' : 'progress-primary' }} w-full"
                                    value="{{ $partida->getTotalConfirmados() }}"
                                    max="{{ \App\Models\Partida::MAX_JOGADORES }}"></progress>
                                <p class="text-xs text-gray-500 mt-1 text-center">
                                    @if ($partida->temQuorum())
                                        ✅ Quórum atingido
                                    @else
                                        Faltam {{ \App\Models\Partida::MIN_JOGADORES - $partida->getTotalConfirmados() }}
                                        para o mínimo
                                    @endif
                                </p>
                            </div>
                        @endif

                        <!-- Observações -->
                        @if ($partida->observacoes)
                            <div class="mt-3">
                                <p class="text-xs text-gray-500">Observações:</p>
                                <p class="text-sm mt-1">{{ Str::limit($partida->observacoes, 60) }}</p>
                            </div>
                        @endif

                        <!-- Ações -->
                        <div class="card-actions justify-end mt-4">
                            <a href="{{ route('partidas.show', $partida) }}" class="btn btn-primary btn-sm">
                                Ver Detalhes
                            </a>

                            @if ($partida->podeDefinirTimes())
                                <a href="{{ route('partidas.divisao.preview', $partida) }}"
                                    class="btn btn-success btn-sm">
                                    Dividir Times
                                </a>
                            @endif
                        </div>
                    </div>
                </div>
            @endforeach
        </div>

        <!-- Paginação -->
        @if ($partidas->hasPages())
            <div class="mt-6">
                {{ $partidas->links() }}
            </div>
        @endif
    @else
        <div class="card bg-base-100 shadow-sm">
            <div class="card-body p-8 text-center">
                <div class="text-6xl mb-4">⚽</div>
                <h3 class="text-lg font-semibold mb-2">Nenhuma partida encontrada</h3>
                <p class="text-gray-500 mb-4">
                    @if (request()->hasAny(['status', 'tipo']))
                        Tente ajustar os filtros ou
                        <a href="{{ route('partidas.index') }}" class="link">limpe a busca</a>
                    @else
                        Comece criando sua primeira partida
                    @endif
                </p>
                <a href="{{ route('partidas.create') }}" class="btn btn-primary">
                    Criar Primeira Partida
                </a>
            </div>
        </div>
    @endif
@endsection
