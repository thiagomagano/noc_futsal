@extends('layouts.app')

@section('title', 'Editar Partida - Futsal Manager')

@section('breadcrumb')
    <li><a href="{{ route('dashboard') }}">Dashboard</a></li>
    <li><a href="{{ route('partidas.index') }}">Partidas</a></li>
    <li><a href="{{ route('partidas.show', $partida) }}">{{ $partida->data_formatada }}</a></li>
    <li>Editar</li>
@endsection

@section('header')
    <div class="flex items-center justify-between">
        <div>
            <h1 class="text-3xl font-bold text-gray-900">Editar Partida</h1>
            <p class="text-gray-600 mt-1">{{ $partida->data_hora_formatada }}</p>
        </div>
    </div>
@endsection

@section('content')
    <div class="max-w-3xl mx-auto">
        <div class="card bg-base-100 shadow-lg">
            <div class="card-body">
                <form method="POST" action="{{ route('partidas.update', $partida) }}" class="space-y-6">
                    @csrf
                    @method('PATCH')

                    <!-- Informações da Partida -->
                    <div class="space-y-4">
                        <h3 class="text-lg font-semibold flex items-center gap-2">
                            📅 Informações da Partida
                        </h3>

                        <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                            <!-- Data -->
                            <div class="form-control">
                                <label class="label">
                                    <span class="label-text">Data <span class="text-error">*</span></span>
                                </label>
                                <input type="date" name="data"
                                    value="{{ old('data', $partida->data_hora->format('Y-m-d')) }}"
                                    class="input input-bordered @error('data_hora') input-error @enderror" required>
                                @error('data_hora')
                                    <label class="label">
                                        <span class="label-text-alt text-error">{{ $message }}</span>
                                    </label>
                                @enderror
                            </div>

                            <!-- Hora -->
                            <div class="form-control">
                                <label class="label">
                                    <span class="label-text">Horário <span class="text-error">*</span></span>
                                </label>
                                <input type="time" name="hora"
                                    value="{{ old('hora', $partida->data_hora->format('H:i')) }}"
                                    class="input input-bordered @error('data_hora') input-error @enderror" required>
                            </div>
                        </div>

                        <!-- Local -->
                        <div class="form-control">
                            <label class="label">
                                <span class="label-text">Local <span class="text-error">*</span></span>
                            </label>
                            <input type="text" name="local" value="{{ old('local', $partida->local) }}"
                                class="input input-bordered @error('local') input-error @enderror"
                                placeholder="Ex: Quadra do Clube Central" required>
                            @error('local')
                                <label class="label">
                                    <span class="label-text-alt text-error">{{ $message }}</span>
                                </label>
                            @enderror
                        </div>

                        <!-- Status -->
                        <div class="form-control">
                            <label class="label">
                                <span class="label-text">Status <span class="text-error">*</span></span>
                            </label>
                            <select name="status" class="select select-bordered @error('status') select-error @enderror"
                                required>
                                @foreach ($statusOptions as $key => $label)
                                    <option value="{{ $key }}"
                                        {{ old('status', $partida->status) === $key ? 'selected' : '' }}>
                                        {{ $label }}
                                    </option>
                                @endforeach
                            </select>
                            <label class="label">
                                <span class="label-text-alt">Status atual: {{ $partida->status_formatado }}</span>
                            </label>
                            @error('status')
                                <label class="label">
                                    <span class="label-text-alt text-error">{{ $message }}</span>
                                </label>
                            @enderror
                        </div>

                        <!-- Observações -->
                        <div class="form-control">
                            <label class="label">
                                <span class="label-text">Observações</span>
                            </label>
                            <textarea name="observacoes" class="textarea textarea-bordered @error('observacoes') textarea-error @enderror h-24"
                                placeholder="Informações adicionais sobre a partida...">{{ old('observacoes', $partida->observacoes) }}</textarea>
                            <label class="label">
                                <span class="label-text-alt">Máximo 1000 caracteres</span>
                            </label>
                            @error('observacoes')
                                <label class="label">
                                    <span class="label-text-alt text-error">{{ $message }}</span>
                                </label>
                            @enderror
                        </div>
                    </div>

                    <div class="divider"></div>

                    <!-- Informações Adicionais -->
                    <div class="card bg-base-200">
                        <div class="card-body">
                            <h4 class="text-md font-semibold mb-2">📊 Informações Adicionais</h4>
                            <div class="grid grid-cols-1 md:grid-cols-2 gap-4 text-sm">
                                <div>
                                    <strong>Total de Atletas:</strong>
                                    {{ $partida->atletas->count() }}
                                </div>
                                <div>
                                    <strong>Confirmados:</strong>
                                    <span class="{{ $partida->temQuorum() ? 'text-success font-bold' : '' }}">
                                        {{ $partida->getTotalConfirmados() }}
                                    </span>
                                </div>
                                <div>
                                    <strong>Criada em:</strong>
                                    {{ $partida->created_at->format('d/m/Y H:i') }}
                                </div>
                                <div>
                                    <strong>Última atualização:</strong>
                                    {{ $partida->updated_at->format('d/m/Y H:i') }}
                                </div>
                            </div>
                        </div>
                    </div>

                    <!-- Alertas -->
                    @if ($partida->temTimesDefinidos())
                        <div class="alert alert-warning">
                            <svg xmlns="http://www.w3.org/2000/svg" class="stroke-current shrink-0 h-6 w-6" fill="none"
                                viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                    d="M12 9v2m0 4h.01m-6.938 4h13.856c1.54 0 2.502-1.667 1.732-3L13.732 4c-.77-1.333-1.964-1.333-2.732 0L3.082 16c-.77 1.333.192 3 1.732 3z" />
                            </svg>
                            <span>Esta partida já tem times definidos. Edite com cuidado!</span>
                        </div>
                    @endif

                    @if (!$partida->temQuorum())
                        <div class="alert alert-info">
                            <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24"
                                class="stroke-current shrink-0 w-6 h-6">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                    d="M13 16h-1v-4h-1m1-4h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z"></path>
                            </svg>
                            <span>Faltam {{ \App\Models\Partida::MIN_JOGADORES - $partida->getTotalConfirmados() }}
                                confirmações para atingir o quórum mínimo.</span>
                        </div>
                    @endif

                    <!-- Botões de Ação -->
                    <div class="flex flex-col sm:flex-row gap-3 pt-4">
                        <button type="submit" class="btn btn-primary flex-1">
                            <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7">
                                </path>
                            </svg>
                            Salvar Alterações
                        </button>
                        <a href="{{ route('partidas.show', $partida) }}" class="btn btn-ghost flex-1">
                            Cancelar
                        </a>
                    </div>
                </form>
            </div>
        </div>

        <!-- Card de Gerenciamento de Atletas -->
        <div class="card bg-base-100 shadow-lg mt-6">
            <div class="card-body">
                <div class="flex items-center justify-between mb-4">
                    <h3 class="text-lg font-semibold">👥 Gerenciar Atletas</h3>
                    <span class="badge badge-lg">{{ $partida->atletas->count() }} atletas</span>
                </div>

                <div class="alert alert-info">
                    <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24"
                        class="stroke-current shrink-0 w-6 h-6">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                            d="M13 16h-1v-4h-1m1-4h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z"></path>
                    </svg>
                    <span>Para adicionar, remover ou confirmar atletas, <a href="{{ route('partidas.show', $partida) }}"
                            class="link link-primary font-bold">acesse a página de detalhes da partida</a>.</span>
                </div>

                <!-- Lista Resumida de Atletas -->
                @if ($partida->atletas->count() > 0)
                    <div class="mt-4">
                        <p class="text-sm font-medium mb-2">Atletas na partida:</p>
                        <div class="flex flex-wrap gap-2">
                            @foreach ($partida->atletas->take(10) as $atleta)
                                <div class="badge {{ $atleta->pivot->confirmado ? 'badge-success' : 'badge-ghost' }}">
                                    {{ $atleta->apelido ?: $atleta->nome }}
                                </div>
                            @endforeach
                            @if ($partida->atletas->count() > 10)
                                <div class="badge badge-ghost">
                                    +{{ $partida->atletas->count() - 10 }} mais
                                </div>
                            @endif
                        </div>
                    </div>
                @endif
            </div>
        </div>

        <!-- Card de Ações Perigosas -->
        <div class="card bg-base-100 shadow-lg mt-6 border-2 border-error">
            <div class="card-body">
                <h3 class="text-lg font-semibold text-error mb-4">⚠️ Zona de Perigo</h3>

                <div class="space-y-4">
                    @if (!$partida->isFinalizada())
                        <div class="flex items-center justify-between">
                            <div>
                                <p class="font-medium">Cancelar Partida</p>
                                <p class="text-sm text-gray-500">Alterar o status para cancelada</p>
                            </div>
                            <form method="POST" action="{{ route('partidas.update', $partida) }}" class="inline">
                                @csrf
                                @method('PATCH')
                                <input type="hidden" name="data_hora" value="{{ $partida->data_hora }}">
                                <input type="hidden" name="local" value="{{ $partida->local }}">
                                <input type="hidden" name="status" value="cancelada">
                                <input type="hidden" name="observacoes" value="{{ $partida->observacoes }}">
                                <button type="submit" class="btn btn-warning btn-sm"
                                    onclick="return confirm('Tem certeza que deseja cancelar esta partida?')">
                                    Cancelar Partida
                                </button>
                            </form>
                        </div>

                        <div class="divider"></div>
                    @endif

                    <div class="flex items-center justify-between">
                        <div>
                            <p class="font-medium text-error">Deletar Partida</p>
                            <p class="text-sm text-gray-500">Esta ação não pode ser desfeita</p>
                        </div>
                        <form method="POST" action="{{ route('partidas.destroy', $partida) }}" class="inline">
                            @csrf
                            @method('DELETE')
                            <button type="submit" class="btn btn-error btn-sm"
                                onclick="return confirm('ATENÇÃO: Tem certeza que deseja DELETAR permanentemente esta partida? Esta ação não pode ser desfeita!')">
                                Deletar
                            </button>
                        </form>
                    </div>
                </div>
            </div>
        </div>
    </div>
@endsection

@push('scripts')
    <script>
        // Combinar data e hora no submit
        document.querySelector('form').addEventListener('submit', function(e) {
            const dataInput = document.querySelector('input[name="data"]');
            const horaInput = document.querySelector('input[name="hora"]');

            if (dataInput && horaInput && dataInput.value && horaInput.value) {
                // Criar input hidden com data_hora combinada
                const dataHoraInput = document.createElement('input');
                dataHoraInput.type = 'hidden';
                dataHoraInput.name = 'data_hora';
                dataHoraInput.value = dataInput.value + ' ' + horaInput.value + ':00';

                this.appendChild(dataHoraInput);

                // Remover inputs originais para não enviar
                dataInput.removeAttribute('name');
                horaInput.removeAttribute('name');
            }
        });
    </script>
@endpush
