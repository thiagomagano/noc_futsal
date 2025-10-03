@extends('layouts.app')

@section('title', 'Novo Atleta - Futsal Manager')

@section('breadcrumb')
    <li><a href="{{ route('dashboard') }}">Dashboard</a></li>
    <li><a href="{{ route('atletas.index') }}">Atletas</a></li>
    <li>Novo Atleta</li>
@endsection

@section('header')
    <div class="flex items-center justify-between">
        <div>
            <h1 class="text-3xl font-bold text-base-content">Novo Atleta</h1>
            <p class="text-gray-400 mt-1">Cadastre um novo jogador no sistema</p>
        </div>
    </div>
@endsection

@section('content')
    <div class="max-w-2xl mx-auto">
        <div class="card bg-base-100 shadow-lg">
            <div class="card-body">
                <form method="POST" action="{{ route('atletas.store') }}" class="space-y-6" x-data="atletaForm()">
                    @csrf

                    <!-- Informações Básicas -->
                    <div class="space-y-4">
                        <h3 class="text-lg font-semibold flex items-center gap-2">
                            👤 Informações Básicas
                        </h3>

                        <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                            <!-- Nome -->
                            <div class="form-control">
                                <label class="label">
                                    <span class="label-text">Nome Completo <span class="text-error">*</span></span>
                                </label>
                                <input type="text" name="nome" value="{{ old('nome', $atleta->nome) }}"
                                    class="input input-bordered @error('nome') input-error @enderror"
                                    placeholder="Ex: João Silva" required x-model="form.nome">
                                @error('nome')
                                    <label class="label">
                                        <span class="label-text-alt text-error">{{ $message }}</span>
                                    </label>
                                @enderror
                            </div>

                            <!-- Apelido -->
                            <div class="form-control">
                                <label class="label">
                                    <span class="label-text">Apelido</span>
                                </label>
                                <input type="text" name="apelido" value="{{ old('apelido', $atleta->apelido) }}"
                                    class="input input-bordered @error('apelido') input-error @enderror"
                                    placeholder="Ex: Joãozinho" x-model="form.apelido">
                                @error('apelido')
                                    <label class="label">
                                        <span class="label-text-alt text-error">{{ $message }}</span>
                                    </label>
                                @enderror
                            </div>
                        </div>

                        <!-- Telefone -->
                        <div class="form-control">
                            <label class="label">
                                <span class="label-text">Telefone/WhatsApp <span class="text-error">*</span></span>
                            </label>
                            <input type="tel" name="telefone" value="{{ old('telefone', $atleta->telefone) }}"
                                class="input input-bordered @error('telefone') input-error @enderror"
                                placeholder="(11) 99999-9999" required x-model="form.telefone" x-on:input="formatTelefone">
                            @error('telefone')
                                <label class="label">
                                    <span class="label-text-alt text-error">{{ $message }}</span>
                                </label>
                            @enderror
                        </div>
                    </div>

                    <div class="divider"></div>

                    <!-- Informações de Jogo -->
                    <div class="space-y-4">
                        <h3 class="text-lg font-semibold flex items-center gap-2">
                            ⚽ Informações de Jogo
                        </h3>

                        <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                            <!-- Posição -->
                            <div class="form-control">
                                <label class="label">
                                    <span class="label-text">Posição Preferida <span class="text-error">*</span></span>
                                </label>
                                <select name="posicao"
                                    class="select select-bordered @error('posicao') select-error @enderror" required
                                    x-model="form.posicao">
                                    <option value="">Selecione uma posição</option>
                                    @foreach ($posicoes as $key => $label)
                                        <option value="{{ $key }}"
                                            {{ old('posicao', $atleta->posicao) === $key ? 'selected' : '' }}>
                                            {{ $label }}
                                        </option>
                                    @endforeach
                                </select>
                                @error('posicao')
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
                                <select name="status"
                                    class="select select-bordered @error('status') select-error @enderror" required
                                    x-model="form.status">
                                    @foreach ($statusOptions as $key => $label)
                                        <option value="{{ $key }}"
                                            {{ old('status', $atleta->status ?? 'ativo') === $key ? 'selected' : '' }}>
                                            {{ $label }}
                                        </option>
                                    @endforeach
                                </select>
                                @error('status')
                                    <label class="label">
                                        <span class="label-text-alt text-error">{{ $message }}</span>
                                    </label>
                                @enderror
                            </div>
                        </div>

                        <!-- Nível de Habilidade -->
                        <div class="form-control">
                            <label class="label">
                                <span class="label-text">Nível de Habilidade <span class="text-error">*</span></span>
                            </label>
                            <div class="p-4 border rounded-lg bg-base-50">
                                <x-star-rating :value="old('nivel_habilidade', $atleta->nivel_habilidade ?? 3)" name="nivel_habilidade" />
                                <p class="text-sm text-gray-500 mt-2">
                                    Avalie o nível de habilidade geral do jogador de 1 a 5 estrelas
                                </p>
                            </div>
                            @error('nivel_habilidade')
                                <label class="label">
                                    <span class="label-text-alt text-error">{{ $message }}</span>
                                </label>
                            @enderror
                        </div>
                    </div>

                    <div class="divider"></div>

                    <!-- Observações -->
                    <div class="form-control">
                        <label class="label">
                            <span class="label-text">Observações</span>
                        </label>
                        <textarea name="observacoes" class="textarea textarea-bordered @error('observacoes') textarea-error @enderror h-24"
                            placeholder="Informações adicionais sobre o atleta..." x-model="form.observacoes">{{ old('observacoes', $atleta->observacoes) }}</textarea>
                        <label class="label">
                            <span class="label-text-alt">Máximo 1000 caracteres</span>
                            <span class="label-text-alt" x-text="`${form.observacoes?.length || 0}/1000`"></span>
                        </label>
                        @error('observacoes')
                            <label class="label">
                                <span class="label-text-alt text-error">{{ $message }}</span>
                            </label>
                        @enderror
                    </div>

                    <!-- Preview -->
                    <div class="card bg-base-200 border-2 border-dashed" x-show="showPreview()">
                        <div class="card-body">
                            <h4 class="text-md font-semibold mb-3">📋 Preview do Atleta</h4>
                            <div class="grid grid-cols-1 md:grid-cols-2 gap-4 text-sm">
                                <div>
                                    <strong>Nome:</strong>
                                    <span x-text="form.nome || 'N/A'"></span>
                                    <span x-show="form.apelido" x-text="`(${form.apelido})`"></span>
                                </div>
                                <div>
                                    <strong>Telefone:</strong>
                                    <span x-text="form.telefone || 'N/A'"></span>
                                </div>
                                <div>
                                    <strong>Posição:</strong>
                                    <span x-text="getPosicaoLabel() || 'N/A'"></span>
                                </div>
                                <div>
                                    <strong>Status:</strong>
                                    <span x-text="getStatusLabel() || 'N/A'"></span>
                                </div>
                            </div>
                        </div>
                    </div>

                    <!-- Botões de Ação -->
                    <div class="flex flex-col sm:flex-row gap-3 pt-4">
                        <button type="submit" class="btn btn-primary flex-1">
                            <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7">
                                </path>
                            </svg>
                            Salvar Atleta
                        </button>
                        <a href="{{ route('atletas.index') }}" class="btn btn-ghost flex-1">
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
        function atletaForm() {
            return {
                form: {
                    nome: @json(old('nome', $atleta->nome ?? '')),
                    apelido: @json(old('apelido', $atleta->apelido ?? '')),
                    telefone: @json(old('telefone', $atleta->telefone ?? '')),
                    posicao: @json(old('posicao', $atleta->posicao ?? '')),
                    status: @json(old('status', $atleta->status ?? 'ativo')),
                    observacoes: @json(old('observacoes', $atleta->observacoes ?? ''))
                },

                posicoes: @json($posicoes),
                statusOptions: @json($statusOptions),

                formatTelefone() {
                    let value = this.form.telefone.replace(/\D/g, '');
                    if (value.length >= 11) {
                        this.form.telefone = value.replace(/(\d{2})(\d{5})(\d{4})/, '($1) $2-$3');
                    } else if (value.length >= 7) {
                        this.form.telefone = value.replace(/(\d{2})(\d{4})(\d+)/, '($1) $2-$3');
                    } else if (value.length >= 3) {
                        this.form.telefone = value.replace(/(\d{2})(\d+)/, '($1) $2');
                    }
                },

                showPreview() {
                    return this.form.nome || this.form.telefone || this.form.posicao;
                },

                getPosicaoLabel() {
                    return this.posicoes[this.form.posicao] || '';
                },

                getStatusLabel() {
                    return this.statusOptions[this.form.status] || '';
                }
            }
        }
    </script>
@endpush
