@extends('layouts.app')

@section('title', 'Atletas - Futsal Manager')

@section('breadcrumb')
    <li><a href="{{ route('dashboard') }}">Dashboard</a></li>
    <li>Atletas</li>
@endsection

@section('header')
    <div class="flex flex-col lg:flex-row lg:items-center lg:justify-between gap-4">
        <div>
            <h1 class="text-3xl font-bold text-base-content">Atletas</h1>
            <p class="text-gray-600 mt-1">Gerencie os jogadores do seu time</p>
        </div>
        <div class="flex gap-2">
            <a href="{{ route('atletas.create') }}" class="btn btn-primary">
                <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4v16m8-8H4"></path>
                </svg>
                Novo Atleta
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
                        d="M17 20h5v-2a3 3 0 00-5.356-1.857M17 20H7m10 0v-2c0-.656-.126-1.283-.356-1.857M7 20H2v-2a3 3 0 015.356-1.857M7 20v-2c0-.656.126-1.283.356-1.857m0 0a5.002 5.002 0 019.288 0M15 7a3 3 0 11-6 0 3 3 0 016 0zm6 3a2 2 0 11-4 0 2 2 0 014 0zM7 10a2 2 0 11-4 0 2 2 0 014 0z" />
                </svg>
            </div>
            <div class="stat-title">Total de Atletas</div>
            <div class="stat-value">{{ $atletas->total() }}</div>
            <div class="stat-desc">Cadastrados no sistema</div>
        </div>

        <div class="stat bg-base-200 rounded-lg">
            <div class="stat-figure text-success">
                <svg class="inline-block w-8 h-8 stroke-current" fill="none" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                        d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z" />
                </svg>
            </div>
            <div class="stat-title">Ativos</div>
            <div class="stat-value text-success">{{ $atletas->where('status', 'ativo')->count() }}</div>
            <div class="stat-desc">Disponíveis para jogar</div>
        </div>

        <div class="stat bg-base-200 rounded-lg">
            <div class="stat-figure text-warning">
                <svg class="inline-block w-8 h-8 stroke-current" fill="none" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                        d="M12 9v2m0 4h.01m-6.938 4h13.856c1.54 0 2.502-1.667 1.732-2.5L13.732 4c-.77-.833-1.964-.833-2.732 0L4.082 14.5c-.77.833.192 2.5 1.732 2.5z" />
                </svg>
            </div>
            <div class="stat-title">Inativos</div>
            <div class="stat-value text-warning">{{ $atletas->where('status', 'inativo')->count() }}</div>
            <div class="stat-desc">Temporariamente fora</div>
        </div>

        <div class="stat bg-base-200 rounded-lg">
            <div class="stat-figure text-info">
                <div class="text-2xl">⭐</div>
            </div>
            <div class="stat-title">Nível Médio</div>
            <div class="stat-value text-info">{{ number_format($atletas->avg('nivel_habilidade'), 1) }}</div>
            <div class="stat-desc">Habilidade do time</div>
        </div>
    </div>

    <!-- Filtros -->
    <div class="card bg-base-100 shadow-sm mb-6">
        <div class="card-body">
            <form method="GET" action="{{ route('atletas.index') }}" class="space-y-4">
                <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-5 gap-4">
                    <!-- Busca -->
                    <div class="form-control">
                        <label class="label">
                            <span class="label-text">Buscar</span>
                        </label>
                        <input type="text" name="search" value="{{ request('search') }}"
                            placeholder="Nome, apelido ou telefone..." class="input input-bordered input-sm">
                    </div>

                    <!-- Posição -->
                    <div class="form-control">
                        <label class="label">
                            <span class="label-text">Posição</span>
                        </label>
                        <select name="posicao" class="select select-bordered select-sm">
                            <option value="">Todas</option>
                            @foreach ($filtros['posicoes'] as $key => $label)
                                <option value="{{ $key }}" {{ request('posicao') === $key ? 'selected' : '' }}>
                                    {{ $label }}
                                </option>
                            @endforeach
                        </select>
                    </div>

                    <!-- Nível -->
                    <div class="form-control">
                        <label class="label">
                            <span class="label-text">Nível</span>
                        </label>
                        <select name="nivel" class="select select-bordered select-sm">
                            <option value="">Todos</option>
                            @foreach ($filtros['niveis'] as $key => $label)
                                <option value="{{ $key }}" {{ request('nivel') == $key ? 'selected' : '' }}>
                                    {{ $key }} - {{ $label }}
                                </option>
                            @endforeach
                        </select>
                    </div>

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

                    <!-- Ações -->
                    <div class="form-control">
                        <label class="label">
                            <span class="label-text">&nbsp;</span>
                        </label>
                        <div class="flex gap-2">
                            <button type="submit" class="btn btn-primary btn-sm flex-1">
                                Filtrar
                            </button>
                            <a href="{{ route('atletas.index') }}" class="btn btn-ghost btn-sm">
                                Limpar
                            </a>
                        </div>
                    </div>
                </div>
            </form>
        </div>
    </div>

    <!-- Tabela de Atletas -->
    <div class="card bg-base-100 shadow-sm">
        <div class="card-body p-0">
            @if ($atletas->count() > 0)
                <div class="overflow-x-auto">
                    <table class="table table-hover">
                        <thead class="bg-base-200">
                            <tr>
                                <th>
                                    <a href="{{ request()->fullUrlWithQuery(['sort' => 'nome', 'direction' => request('direction') === 'asc' ? 'desc' : 'asc']) }}"
                                        class="flex items-center gap-1 hover:text-primary">
                                        Nome
                                        @if (request('sort') === 'nome')
                                            <span class="text-xs">{{ request('direction') === 'asc' ? '▲' : '▼' }}</span>
                                        @endif
                                    </a>
                                </th>
                                <th>
                                    <a href="{{ request()->fullUrlWithQuery(['sort' => 'posicao', 'direction' => request('direction') === 'asc' ? 'desc' : 'asc']) }}"
                                        class="flex items-center gap-1 hover:text-primary">
                                        Posição
                                        @if (request('sort') === 'posicao')
                                            <span class="text-xs">{{ request('direction') === 'asc' ? '▲' : '▼' }}</span>
                                        @endif
                                    </a>
                                </th>
                                <th>
                                    <a href="{{ request()->fullUrlWithQuery(['sort' => 'nivel_habilidade', 'direction' => request('direction') === 'asc' ? 'desc' : 'asc']) }}"
                                        class="flex items-center gap-1 hover:text-primary">
                                        Habilidade
                                        @if (request('sort') === 'nivel_habilidade')
                                            <span class="text-xs">{{ request('direction') === 'asc' ? '▲' : '▼' }}</span>
                                        @endif
                                    </a>
                                </th>
                                <th>Telefone</th>
                                <th>
                                    <a href="{{ request()->fullUrlWithQuery(['sort' => 'status', 'direction' => request('direction') === 'asc' ? 'desc' : 'asc']) }}"
                                        class="flex items-center gap-1 hover:text-primary">
                                        Status
                                        @if (request('sort') === 'status')
                                            <span class="text-xs">{{ request('direction') === 'asc' ? '▲' : '▼' }}</span>
                                        @endif
                                    </a>
                                </th>
                                <th class="text-right">Ações</th>
                            </tr>
                        </thead>
                        <tbody>
                            @foreach ($atletas as $atleta)
                                <tr class="hover">
                                    <td>
                                        <div>
                                            <div class="font-semibold">{{ $atleta->nome }}</div>
                                            @if ($atleta->apelido)
                                                <div class="text-sm text-gray-500">"{{ $atleta->apelido }}"</div>
                                            @endif
                                        </div>
                                    </td>
                                    <td>
                                        <span class="badge badge-outline">{{ $atleta->posicao_formatada }}</span>
                                    </td>
                                    <td>
                                        <x-star-rating :value="$atleta->nivel_habilidade" readonly size="sm" />

                                    </td>
                                    <td>
                                        <a href="tel:{{ $atleta->telefone }}" class="link link-primary text-sm">
                                            {{ $atleta->telefone }}
                                        </a>
                                    </td>
                                    <td>
                                        <span class="badge {{ $atleta->isAtivo() ? 'badge-success' : 'badge-warning' }}">
                                            {{ $atleta->status_formatado }}
                                        </span>
                                    </td>
                                    <td class="text-right">
                                        <div class="dropdown dropdown-end">
                                            <div tabindex="0" role="button" class="btn btn-ghost btn-sm">
                                                ⋮
                                            </div>
                                            <ul tabindex="0"
                                                class="dropdown-content z-[1] menu p-2 shadow bg-base-100 rounded-box w-52">
                                                <li>
                                                    <a href="{{ route('atletas.show', $atleta) }}">
                                                        👁️ Visualizar
                                                    </a>
                                                </li>
                                                <li>
                                                    <a href="{{ route('atletas.edit', $atleta) }}">
                                                        ✏️ Editar
                                                    </a>
                                                </li>
                                                <li>
                                                    <form action="{{ route('atletas.toggle-status', $atleta) }}"
                                                        method="POST" class="inline">
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
                                                        onsubmit="return confirm('Tem certeza que deseja remover este atleta?')"
                                                        class="inline">
                                                        @csrf
                                                        @method('DELETE')
                                                        <button type="submit" class="w-full text-left text-error">
                                                            🗑️ Remover
                                                        </button>
                                                    </form>
                                                </li>
                                            </ul>
                                        </div>
                                    </td>
                                </tr>
                            @endforeach
                        </tbody>
                    </table>
                </div>

                <!-- Paginação -->
                @if ($atletas->hasPages())
                    <div class="p-4 border-t">
                        {{ $atletas->links() }}
                    </div>
                @endif
            @else
                <div class="p-8 text-center">
                    <div class="text-6xl mb-4">⚽</div>
                    <h3 class="text-lg font-semibold mb-2">Nenhum atleta encontrado</h3>
                    <p class="text-gray-500 mb-4">
                        @if (request()->hasAny(['search', 'posicao', 'nivel', 'status']))
                            Tente ajustar os filtros ou
                            <a href="{{ route('atletas.index') }}" class="link">limpe a busca</a>
                        @else
                            Comece cadastrando seu primeiro jogador
                        @endif
                    </p>
                    <a href="{{ route('atletas.create') }}" class="btn btn-primary">
                        Cadastrar Primeiro Atleta
                    </a>
                </div>
            @endif
        </div>
    </div>
@endsection
