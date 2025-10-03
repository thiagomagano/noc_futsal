@extends('layouts.app')

@section('title', 'Nova Partida - Futsal Manager')

@section('breadcrumb')
    <li><a href="{{ route('dashboard') }}">Dashboard</a></li>
    <li><a href="{{ route('partidas.index') }}">Partidas</a></li>
    <li>Nova Partida</li>
@endsection

@section('header')
    <div class="flex items-center justify-between">
        <div>
            <h1 class="text-3xl font-bold text-gray-200">Nova Partida</h1>
            <p class="text-gray-400 mt-1">Organize uma nova partida para o time</p>
        </div>
    </div>
@endsection

@section('content')
    <div class="max-w-3xl mx-auto">
        <div class="card bg-base-100 shadow-lg">
            <div class="card-body">
                <form method="POST" action="{{ route('partidas.store') }}" class="space-y-6">
                    @csrf

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
                                <input type="date" name="data_hora" value="{{ old('data_hora') }}"
                                    class="input input-bordered @error('data_hora') input-error @enderror" required
                                    min="{{ date('Y-m-d') }}">
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
                                <input type="time" name="hora" value="{{ old('hora', '18:40') }}"
                                    class="input input-bordered @error('data_hora') input-error @enderror" required>
                            </div>
                        </div>

                        <!-- Local -->
                        <div class="form-control">
                            <label class="label">
                                <span class="label-text">Local <span class="text-error">*</span></span>
                            </label>
                            <input type="text" name="local" value="{{ old('local', 'Gol Esportes') }}"
                                class="input input-bordered @error('local') input-error @enderror"
                                placeholder="Ex: Gol Esportes" required>
                            @error('local')
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
                                placeholder="Informações adicionais sobre a partida...">{{ old('observacoes') }}</textarea>
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

                    <!-- Seleção de Atletas (Opcional) -->
                    <div class="space-y-4">
                        <div class="flex items-center justify-between">
                            <h3 class="text-lg font-semibold flex items-center gap-2">
                                👥 Selecionar Atletas (Opcional)
                            </h3>
                            <button type="button" onclick="selecionarTodos()" class="btn btn-ghost btn-sm">
                                Selecionar Todos
                            </button>
                        </div>

                        <div class="alert alert-info">
                            <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24"
                                class="stroke-current shrink-0 w-6 h-6">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                    d="M13 16h-1v-4h-1m1-4h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z"></path>
                            </svg>
                            <span>Você pode adicionar atletas agora ou depois. Os atletas precisarão confirmar
                                presença.</span>
                        </div>

                        @if ($atletasAtivos->count() > 0)
                            <div
                                class="grid grid-cols-1 md:grid-cols-2 gap-3 max-h-96 overflow-y-auto p-4 border rounded-lg">
                                @foreach ($atletasAtivos as $atleta)
                                    <label
                                        class="label cursor-pointer justify-start gap-3 hover:bg-base-200 p-3 rounded-lg transition-colors">
                                        <input type="checkbox" name="atletas[]" value="{{ $atleta->id }}"
                                            class="checkbox checkbox-primary"
                                            {{ in_array($atleta->id, old('atletas', [])) ? 'checked' : '' }}>
                                        <div class="flex-1">
                                            <span class="font-medium">{{ $atleta->nome }}</span>
                                            @if ($atleta->apelido)
                                                <span class="text-sm text-gray-500">({{ $atleta->apelido }})</span>
                                            @endif
                                            <div class="flex items-center gap-2 mt-1">
                                                <span
                                                    class="badge badge-outline badge-xs">{{ $atleta->posicao_formatada }}</span>
                                                <span class="text-xs text-gray-500">
                                                    {{ str_repeat('⭐', $atleta->nivel_habilidade) }}
                                                </span>
                                            </div>
                                        </div>
                                    </label>
                                @endforeach
                            </div>

                            <div class="text-sm text-gray-500 text-center">
                                Total de atletas ativos: {{ $atletasAtivos->count() }}
                            </div>
                        @else
                            <div class="alert alert-warning">
                                <svg xmlns="http://www.w3.org/2000/svg" class="stroke-current shrink-0 h-6 w-6"
                                    fill="none" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                        d="M12 9v2m0 4h.01m-6.938 4h13.856c1.54 0 2.502-1.667 1.732-3L13.732 4c-.77-1.333-1.964-1.333-2.732 0L3.082 16c-.77 1.333.192 3 1.732 3z" />
                                </svg>
                                <span>Nenhum atleta ativo encontrado. <a href="{{ route('atletas.create') }}"
                                        class="link">Cadastre atletas</a> primeiro.</span>
                            </div>
                        @endif

                        @error('atletas')
                            <label class="label">
                                <span class="label-text-alt text-error">{{ $message }}</span>
                            </label>
                        @enderror
                    </div>

                    <!-- Botões de Ação -->
                    <div class="flex flex-col sm:flex-row gap-3 pt-4">
                        <button type="submit" class="btn btn-primary flex-1">
                            <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7">
                                </path>
                            </svg>
                            Criar Partida
                        </button>
                        <a href="{{ route('partidas.index') }}" class="btn btn-ghost flex-1">
                            Cancelar
                        </a>
                    </div>
                </form>
            </div>
        </div>
    </div>
@endsection

@push('scripts')
    <script>
        // Combinar data e hora no submit
        document.querySelector('form').addEventListener('submit', function(e) {
            const dataInput = document.querySelector('input[name="data_hora"]');
            const horaInput = document.querySelector('input[name="hora"]');

            if (dataInput.value && horaInput.value) {
                dataInput.value = dataInput.value + ' ' + horaInput.value + ':00';
            }
        });

        // Selecionar todos os atletas
        function selecionarTodos() {
            const checkboxes = document.querySelectorAll('input[name="atletas[]"]');
            const todosChecados = Array.from(checkboxes).every(cb => cb.checked);

            checkboxes.forEach(checkbox => {
                checkbox.checked = !todosChecados;
            });
        }
    </script>
@endpush
