@extends('layouts.app')

@section('title', 'Partida ' . $partida->data_formatada . ' - Futsal Manager')

@section('breadcrumb')
    <li><a href="{{ route('dashboard') }}">Dashboard</a></li>
    <li><a href="{{ route('partidas.index') }}">Partidas</a></li>
    <li>{{ $partida->data_formatada }}</li>
@endsection

@section('header')
    <div class="flex flex-col lg:flex-row lg:items-center lg:justify-between gap-4">
        <div>
            <h1 class="text-3xl font-bold text-gray-900">{{ $partida->data_hora_formatada }}</h1>
            <div class="flex items-center gap-4 mt-2">
                <span
                    class="badge 
                    @if ($partida->isAberta()) badge-info badge-lg
                    @elseif($partida->isConfirmada()) badge-success badge-lg
                    @elseif($partida->temTimesDefinidos()) badge-primary badge-lg
                    @elseif($partida->isFinalizada()) badge-neutral badge-lg
                    @else badge-warning badge-lg @endif
                ">
                    {{ $partida->status_formatado }}
                </span>
                <span class="text-gray-600">📍 {{ $partida->local }}</span>
            </div>
        </div>
        <div class="flex gap-2">
            @if (!$partida->isFinalizada() && !$partida->isCancelada())
                <a href="{{ route('partidas.edit', $partida) }}" class="btn btn-ghost">
                    ✏️ Editar
                </a>
            @endif

            @if ($partida->podeDefinirTimes())
                <a href="{{ route('partidas.divisao.preview', $partida) }}" class="btn btn-success">
                    ⚖️ Dividir Times
                </a>
            @endif

            @if ($partida->temTimesDefinidos())
                <a href="{{ route('partidas.mensagem-whatsapp', $partida) }}" class="btn btn-primary">
                    💬 Gerar Mensagem
                </a>
            @endif
        </div>
    </div>
@endsection

@section('content')
    <div class="grid grid-cols-1 lg:grid-cols-3 gap-6">
        <!-- Coluna Principal -->
        <div class="lg:col-span-2 space-y-6">
            <!-- Informações da Partida -->
            <div class="card bg-base-100 shadow-lg">
                <div class="card-body">
                    <h2 class="card-title text-xl mb-4">
                        📋 Informações da Partida
                    </h2>

                    <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                        <div>
                            <label class="text-sm font-medium text-gray-500">Data</label>
                            <p class="text-lg font-semibold">{{ $partida->data_formatada }}</p>
                        </div>

                        <div>
                            <label class="text-sm font-medium text-gray-500">Horário</label>
                            <p class="text-lg font-semibold">{{ $partida->hora_formatada }}</p>
                        </div>

                        <div class="md:col-span-2">
                            <label class="text-sm font-medium text-gray-500">Local</label>
                            <p class="text-lg">{{ $partida->local }}</p>
                        </div>
                    </div>

                    @if ($partida->observacoes)
                        <div class="divider"></div>
                        <div>
                            <label class="text-sm font-medium text-gray-500">Observações</label>
                            <div class="bg-base-200 p-4 rounded-lg mt-2">
                                <p class="whitespace-pre-wrap">{{ $partida->observacoes }}</p>
                            </div>
                        </div>
                    @endif
                </div>
            </div>

            <!-- Lista de Atletas -->
            <div class="card bg-base-100 shadow-lg">
                <div class="card-body">
                    <div class="flex items-center justify-between mb-4">
                        <h2 class="card-title text-xl">
                            👥 Atletas da Partida
                        </h2>

                        @if (!$partida->temTimesDefinidos())
                            <button onclick="modalAdicionarAtletas.showModal()" class="btn btn-sm btn-primary">
                                + Adicionar Atletas
                            </button>
                        @endif
                    </div>

                    <!-- Estatísticas -->
                    <div class="stats stats-vertical lg:stats-horizontal shadow mb-4">
                        <div class="stat">
                            <div class="stat-title">Total</div>
                            <div class="stat-value text-2xl">{{ $partida->atletas->count() }}</div>
                            <div class="stat-desc">Atletas selecionados</div>
                        </div>

                        <div class="stat">
                            <div class="stat-title">Confirmados</div>
                            <div class="stat-value text-2xl {{ $partida->temQuorum() ? 'text-success' : '' }}">
                                {{ $partida->getTotalConfirmados() }}
                            </div>
                            <div class="stat-desc">
                                @if ($partida->temQuorum())
                                    ✅ Quórum atingido
                                @else
                                    Mín: {{ \App\Models\Partida::MIN_JOGADORES }} | Máx:
                                    {{ \App\Models\Partida::MAX_JOGADORES }}
                                @endif
                            </div>
                        </div>

                        <div class="stat">
                            <div class="stat-title">Pendentes</div>
                            <div class="stat-value text-2xl text-warning">
                                {{ $partida->atletas->count() - $partida->getTotalConfirmados() }}
                            </div>
                            <div class="stat-desc">Aguardando confirmação</div>
                        </div>
                    </div>

                    <!-- Progress Bar -->
                    @if ($partida->getTotalConfirmados() > 0)
                        <div class="mb-4">
                            <progress
                                class="progress {{ $partida->temQuorum() ? 'progress-success' : 'progress-primary' }} w-full"
                                value="{{ $partida->getTotalConfirmados() }}"
                                max="{{ \App\Models\Partida::MAX_JOGADORES }}"></progress>
                            <div class="flex justify-between text-xs text-gray-500 mt-1">
                                <span>{{ \App\Models\Partida::MIN_JOGADORES }} mínimo</span>
                                <span>{{ $partida->getTotalConfirmados() }}/{{ \App\Models\Partida::MAX_JOGADORES }}</span>
                            </div>
                        </div>
                    @endif

                    <!-- Lista de Atletas -->
                    @if ($partida->atletas->count() > 0)
                        <div class="overflow-x-auto">
                            <table class="table table-sm">
                                <thead class="bg-base-200">
                                    <tr>
                                        <th>Atleta</th>
                                        <th>Posição</th>
                                        <th>Habilidade</th>
                                        <th>Status</th>
                                        @if (!$partida->temTimesDefinidos())
                                            <th>Ações</th>
                                        @endif
                                    </tr>
                                </thead>
                                <tbody>
                                    @foreach ($partida->atletas->sortBy('nome') as $atleta)
                                        <tr class="hover">
                                            <td>
                                                <div>
                                                    <div class="font-semibold">{{ $atleta->nome }}</div>
                                                    @if ($atleta->apelido)
                                                        <div class="text-xs text-gray-500">"{{ $atleta->apelido }}"</div>
                                                    @endif
                                                </div>
                                            </td>
                                            <td>
                                                <span
                                                    class="badge badge-outline badge-xs">{{ $atleta->posicao_formatada }}</span>
                                            </td>
                                            <td>
                                                <span
                                                    class="text-sm">{{ str_repeat('⭐', $atleta->nivel_habilidade) }}</span>
                                            </td>
                                            <td>
                                                @if ($atleta->pivot->confirmado)
                                                    <span class="badge badge-success badge-sm">Confirmado</span>
                                                @else
                                                    <span class="badge badge-warning badge-sm">Pendente</span>
                                                @endif
                                            </td>
                                            @if (!$partida->temTimesDefinidos())
                                                <td>
                                                    <div class="flex gap-1">
                                                        <form
                                                            action="{{ route('partidas.toggle-confirmacao', [$partida, $atleta]) }}"
                                                            method="POST" class="inline">
                                                            @csrf
                                                            @method('PATCH')
                                                            <button type="submit" class="btn btn-ghost btn-xs"
                                                                title="{{ $atleta->pivot->confirmado ? 'Cancelar confirmação' : 'Confirmar presença' }}">
                                                                {{ $atleta->pivot->confirmado ? '❌' : '✅' }}
                                                            </button>
                                                        </form>

                                                        <form
                                                            action="{{ route('partidas.remover-atleta', [$partida, $atleta]) }}"
                                                            method="POST"
                                                            onsubmit="return confirm('Remover este atleta da partida?')"
                                                            class="inline">
                                                            @csrf
                                                            @method('DELETE')
                                                            <button type="submit" class="btn btn-ghost btn-xs text-error"
                                                                title="Remover">
                                                                🗑️
                                                            </button>
                                                        </form>
                                                    </div>
                                                </td>
                                            @endif
                                        </tr>
                                    @endforeach
                                </tbody>
                            </table>
                        </div>
                    @else
                        <div class="text-center py-8 text-gray-500">
                            <div class="text-4xl mb-2">👥</div>
                            <p>Nenhum atleta adicionado ainda</p>
                            <button onclick="modalAdicionarAtletas.showModal()" class="btn btn-primary btn-sm mt-4">
                                Adicionar Atletas
                            </button>
                        </div>
                    @endif
                </div>
            </div>

            <!-- Times Definidos -->
            @if ($partida->temTimesDefinidos())
                <div class="card bg-base-100 shadow-lg">
                    <div class="card-body">
                        <div class="flex items-center justify-between mb-4">
                            <h2 class="card-title text-xl">
                                ⚽ Times Definidos
                            </h2>
                            <button onclick="modalRedistribuir.showModal()" class="btn btn-sm btn-ghost">
                                🔄 Redistribuir
                            </button>
                        </div>

                        <!-- Estatísticas de Balanceamento -->
                        @if ($balanceamento)
                            <div class="stats stats-vertical lg:stats-horizontal shadow mb-4">
                                <div class="stat bg-neutral text-neutral-content">
                                    <div class="stat-title text-neutral-content opacity-80">Time Preto</div>
                                    <div class="stat-value text-2xl">{{ $balanceamento['preto']['total_jogadores'] }}</div>
                                    <div class="stat-desc text-neutral-content opacity-80">
                                        Média: {{ $balanceamento['preto']['media_habilidade'] }} ⭐
                                    </div>
                                </div>

                                <div class="stat">
                                    <div class="stat-title">Time Branco</div>
                                    <div class="stat-value text-2xl">{{ $balanceamento['branco']['total_jogadores'] }}
                                    </div>
                                    <div class="stat-desc">
                                        Média: {{ $balanceamento['branco']['media_habilidade'] }} ⭐
                                    </div>
                                </div>
                            </div>
                        @endif

                        <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                            <!-- Time Preto -->
                            <div class="border-2 border-neutral rounded-lg p-4 bg-neutral text-neutral-content">
                                <h3 class="font-bold text-lg mb-3 flex items-center gap-2">
                                    ⚫ Time Preto
                                </h3>
                                <ul class="space-y-2">
                                    @foreach ($partida->timePreto as $index => $atleta)
                                        <li class="flex items-center gap-2">
                                            <span class="font-semibold">{{ $index + 1 }}.</span>
                                            <span>{{ $atleta->apelido ?: $atleta->nome }}</span>
                                            <span
                                                class="ml-auto text-sm">{{ str_repeat('⭐', $atleta->nivel_habilidade) }}</span>
                                        </li>
                                    @endforeach
                                </ul>
                            </div>

                            <!-- Time Branco -->
                            <div class="border-2 border-base-300 rounded-lg p-4">
                                <h3 class="font-bold text-lg mb-3 flex items-center gap-2">
                                    ⚪ Time Branco
                                </h3>
                                <ul class="space-y-2">
                                    @foreach ($partida->timeBranco as $index => $atleta)
                                        <li class="flex items-center gap-2">
                                            <span class="font-semibold">{{ $index + 1 }}.</span>
                                            <span>{{ $atleta->apelido ?: $atleta->nome }}</span>
                                            <span
                                                class="ml-auto text-sm">{{ str_repeat('⭐', $atleta->nivel_habilidade) }}</span>
                                        </li>
                                    @endforeach
                                </ul>
                            </div>
                        </div>
                    </div>
                </div>
            @endif
        </div>

        <!-- Sidebar -->
        <div class="space-y-6">
            <!-- Ações Rápidas -->
            <div class="card bg-base-100 shadow-lg">
                <div class="card-body">
                    <h3 class="card-title text-lg mb-4">
                        ⚡ Ações Rápidas
                    </h3>

                    <div class="space-y-2">
                        @if (!$partida->isFinalizada() && !$partida->temTimesDefinidos())
                            <a href="{{ route('partidas.edit', $partida) }}"
                                class="btn btn-outline btn-sm w-full justify-start">
                                ✏️ Editar Partida
                            </a>
                        @endif

                        @if ($partida->podeDefinirTimes())
                            <a href="{{ route('partidas.divisao.preview', $partida) }}"
                                class="btn btn-success btn-sm w-full justify-start">
                                ⚖️ Dividir Times
                            </a>
                        @endif

                        @if ($partida->temTimesDefinidos())
                            <a href="{{ route('partidas.mensagem-whatsapp', $partida) }}"
                                class="btn btn-primary btn-sm w-full justify-start">
                                💬 Gerar Mensagem
                            </a>
                        @endif

                        <a href="{{ route('partidas.index') }}" class="btn btn-ghost btn-sm w-full justify-start">
                            ← Voltar para Partidas
                        </a>
                    </div>
                </div>
            </div>

            <!-- Info do Sistema -->
            <div class="card bg-base-100 shadow-lg">
                <div class="card-body">
                    <h3 class="card-title text-lg mb-4">
                        🔧 Informações
                    </h3>

                    <div class="space-y-3">
                        <div>
                            <label class="text-xs font-medium text-gray-500 uppercase tracking-wider">
                                Criada em
                            </label>
                            <p class="text-sm mt-1">
                                {{ $partida->created_at->format('d/m/Y H:i') }}
                            </p>
                        </div>

                        <div>
                            <label class="text-xs font-medium text-gray-500 uppercase tracking-wider">
                                Última atualização
                            </label>
                            <p class="text-sm mt-1">
                                {{ $partida->updated_at->format('d/m/Y H:i') }}
                            </p>
                        </div>

                        <div>
                            <label class="text-xs font-medium text-gray-500 uppercase tracking-wider">
                                ID da Partida
                            </label>
                            <p class="text-sm mt-1 font-mono">#{{ $partida->id }}</p>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <!-- Modal: Adicionar Atletas -->
    <dialog id="modalAdicionarAtletas" class="modal">
        <div class="modal-box w-11/12 max-w-2xl">
            <h3 class="font-bold text-lg mb-4">Adicionar Atletas à Partida</h3>

            <form method="POST" action="{{ route('partidas.adicionar-atletas', $partida) }}">
                @csrf

                @if ($atletasDisponiveis->count() > 0)
                    <div class="grid grid-cols-1 md:grid-cols-2 gap-3 max-h-96 overflow-y-auto p-2">
                        @foreach ($atletasDisponiveis as $atleta)
                            <label
                                class="label cursor-pointer justify-start gap-3 hover:bg-base-200 p-3 rounded-lg transition-colors">
                                <input type="checkbox" name="atletas[]" value="{{ $atleta->id }}"
                                    class="checkbox checkbox-primary">
                                <div class="flex-1">
                                    <span class="font-medium">{{ $atleta->nome }}</span>
                                    @if ($atleta->apelido)
                                        <span class="text-sm text-gray-500">({{ $atleta->apelido }})</span>
                                    @endif
                                    <div class="flex items-center gap-2 mt-1">
                                        <span class="badge badge-outline badge-xs">{{ $atleta->posicao_formatada }}</span>
                                        <span class="text-xs">{{ str_repeat('⭐', $atleta->nivel_habilidade) }}</span>
                                    </div>
                                </div>
                            </label>
                        @endforeach
                    </div>

                    <div class="modal-action">
                        <button type="submit" class="btn btn-primary">Adicionar Selecionados</button>
                        <button type="button" class="btn" onclick="modalAdicionarAtletas.close()">Cancelar</button>
                    </div>
                @else
                    <div class="alert alert-info">
                        <span>Todos os atletas ativos já foram adicionados à partida.</span>
                    </div>
                    <div class="modal-action">
                        <button type="button" class="btn" onclick="modalAdicionarAtletas.close()">Fechar</button>
                    </div>
                @endif
            </form>
        </div>
        <form method="dialog" class="modal-backdrop">
            <button>Fechar</button>
        </form>
    </dialog>

    <!-- Modal: Redistribuir Times -->
    <dialog id="modalRedistribuir" class="modal">
        <div class="modal-box">
            <h3 class="font-bold text-lg mb-4">Redistribuir Times</h3>
            <p class="py-4">Tem certeza que deseja redistribuir os times? A divisão atual será perdida e uma nova será
                gerada.</p>

            <form method="POST" action="{{ route('partidas.divisao.redistribuir', $partida) }}">
                @csrf
                <div class="modal-action">
                    <button type="submit" class="btn btn-warning">Sim, Redistribuir</button>
                    <button type="button" class="btn" onclick="modalRedistribuir.close()">Cancelar</button>
                </div>
            </form>
        </div>
        <form method="dialog" class="modal-backdrop">
            <button>Fechar</button>
        </form>
    </dialog>
@endsection
