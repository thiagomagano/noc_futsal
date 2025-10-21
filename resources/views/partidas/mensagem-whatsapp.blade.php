@extends('layouts.app')

@section('title', 'Mensagem WhatsApp - Futsal Manager')

@section('breadcrumb')
    <li><a href="{{ route('dashboard') }}">Dashboard</a></li>
    <li><a href="{{ route('partidas.index') }}">Partidas</a></li>
    <li><a href="{{ route('partidas.show', $partida) }}">{{ $partida->data_formatada }}</a></li>
    <li>Mensagem WhatsApp</li>
@endsection

@section('header')
    <div class="flex flex-col lg:flex-row lg:items-center lg:justify-between gap-4">
        <div>
            <h1 class="text-3xl font-bold text-gray-200">💬 Mensagem para WhatsApp</h1>
            <p class="text-gray-400 mt-1">Copie e cole a mensagem no grupo do time</p>
        </div>
    </div>
@endsection

@section('content')
    <div class="max-w-4xl mx-auto space-y-6">
        <!-- Alertas -->
        <div class="alert alert-success">
            <svg xmlns="http://www.w3.org/2000/svg" class="stroke-current shrink-0 h-6 w-6" fill="none"
                viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                    d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z" />
            </svg>
            <div>
                <h3 class="font-bold">Times definidos com sucesso!</h3>
                <div class="text-sm">Use o botão "Copiar Mensagem" e cole no grupo do WhatsApp.</div>
            </div>
        </div>

        <!-- Preview da Mensagem -->
        <div class="grid grid-cols-1 lg:grid-cols-2 gap-6">
            <!-- Card Esquerdo - Preview Visual -->
            <div class="card bg-base-100 shadow-xl">
                <div class="card-body">
                    <h2 class="card-title mb-4">📱 Preview da Mensagem</h2>

                    <div class="mockup-phone border-primary">
                        <div class="camera"></div>
                        <div class="display">
                            <div
                                class="artboard artboard-demo phone-1 bg-gradient-to-b from-green-50 to-green-100 p-4 overflow-y-auto">
                                <div class="chat chat-start">
                                    <div
                                        class="chat-bubble chat-bubble-success max-w-full whitespace-pre-wrap text-sm font-mono">
                                        {{ $mensagem }}</div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>

            <!-- Card Direito - Ações e Info -->
            <div class="space-y-6">
                <!-- Botão de Copiar -->
                <div class="card bg-primary text-primary-content shadow-xl">
                    <div class="card-body">
                        <h2 class="card-title mb-4">📋 Copiar Mensagem</h2>
                        <p class="text-sm mb-4 opacity-90">
                            Clique no botão abaixo para copiar a mensagem formatada para a área de transferência.
                        </p>

                        <button onclick="copiarMensagem()" id="btnCopiar" class="btn btn-accent btn-lg w-full">
                            <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                    d="M8 16H6a2 2 0 01-2-2V6a2 2 0 012-2h8a2 2 0 012 2v2m-6 12h8a2 2 0 002-2v-8a2 2 0 00-2-2h-8a2 2 0 00-2 2v8a2 2 0 002 2z" />
                            </svg>
                            Copiar Mensagem
                        </button>

                        <div id="mensagemCopiada" class="alert alert-success mt-4 hidden">
                            <svg xmlns="http://www.w3.org/2000/svg" class="stroke-current shrink-0 h-6 w-6" fill="none"
                                viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                    d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z" />
                            </svg>
                            <span>Mensagem copiada! Cole no WhatsApp.</span>
                        </div>
                    </div>
                </div>

                <!-- Estatísticas dos Times -->
                <div class="card bg-base-100 shadow-xl">
                    <div class="card-body">
                        <h2 class="card-title mb-4">📊 Resumo dos Times</h2>

                        <div class="space-y-4">
                            <!-- Time Preto -->
                            <div class="bg-neutral text-neutral-content p-4 rounded-lg">
                                <div class="flex items-center justify-between mb-2">
                                    <h3 class="font-bold flex items-center gap-2">
                                        ⚫ Time Preto
                                    </h3>
                                    <span class="badge badge-lg">{{ $balanceamento['preto']['total_jogadores'] }}
                                        jogadores</span>
                                </div>
                                <div class="text-sm opacity-90">
                                    <p>Habilidade Total: {{ $balanceamento['preto']['soma_habilidade'] }}</p>
                                    <p>Média: {{ $balanceamento['preto']['media_habilidade'] }} ⭐</p>
                                </div>
                            </div>

                            <!-- Time Branco -->
                            <div class="bg-base-200 p-4 rounded-lg">
                                <div class="flex items-center justify-between mb-2">
                                    <h3 class="font-bold flex items-center gap-2">
                                        ⚪ Time Branco
                                    </h3>
                                    <span class="badge badge-lg">{{ $balanceamento['branco']['total_jogadores'] }}
                                        jogadores</span>
                                </div>
                                <div class="text-sm text-gray-400">
                                    <p>Habilidade Total: {{ $balanceamento['branco']['soma_habilidade'] }}</p>
                                    <p>Média: {{ $balanceamento['branco']['media_habilidade'] }} ⭐</p>
                                </div>
                            </div>

                            <!-- Balanceamento -->
                            <div class="alert">
                                <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24"
                                    class="stroke-current shrink-0 w-6 h-6">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                        d="M13 16h-1v-4h-1m1-4h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z"></path>
                                </svg>
                                <span class="text-sm">
                                    Diferença de média:
                                    {{ abs($balanceamento['preto']['media_habilidade'] - $balanceamento['branco']['media_habilidade']) }}
                                    ⭐
                                </span>
                            </div>
                        </div>
                    </div>
                </div>

                <!-- Informações da Partida -->
                <div class="card bg-base-100 shadow-xl">
                    <div class="card-body">
                        <h2 class="card-title mb-4">ℹ️ Informações da Partida</h2>

                        <div class="space-y-2 text-sm">
                            <div class="flex items-center gap-2">
                                <svg class="w-4 h-4 text-gray-500" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                        d="M8 7V3m8 4V3m-9 8h10M5 21h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v12a2 2 0 002 2z" />
                                </svg>
                                <span>{{ $partida->data_formatada }}</span>
                            </div>

                            <div class="flex items-center gap-2">
                                <svg class="w-4 h-4 text-gray-500" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                        d="M12 8v4l3 3m6-3a9 9 0 11-18 0 9 9 0 0118 0z" />
                                </svg>
                                <span>{{ $partida->hora_formatada }}</span>
                            </div>

                            <div class="flex items-center gap-2">
                                <svg class="w-4 h-4 text-gray-500" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                        d="M17.657 16.657L13.414 20.9a1.998 1.998 0 01-2.827 0l-4.244-4.243a8 8 0 1111.314 0z" />
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                        d="M15 11a3 3 0 11-6 0 3 3 0 016 0z" />
                                </svg>
                                <span>{{ $partida->local }}</span>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <!-- Texto Completo (oculto para copiar) -->
        <textarea id="mensagemTexto" class="hidden">{{ $mensagem }}</textarea>

        <!-- Listagem dos Times (Desktop) -->
        <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
            <!-- Time Preto -->
            <div class="card bg-neutral text-neutral-content shadow-xl">
                <div class="card-body">
                    <h3 class="card-title mb-4">
                        <img class="size-[40px] object-contain" src="/images/logo_noc_preto.png" alt="Logo noc">
                        <span>Time Preto </span>
                    </h3>
                    <ul class="space-y-2">
                        @foreach ($timePreto as $index => $atleta)
                            <li class="flex items-center gap-2 p-2 bg-base-900 rounded">
                                <div class="avatar avatar-placeholder">
                                    <div class="bg-base-content text-base-300 rounded-full w-10">
                                        <span
                                            class="text-lg font-bold">{{ mb_strtoupper(mb_substr($atleta->apelido ?: $atleta->nome, 0, 1)) }}</span>
                                    </div>
                                </div>
                                <span class="flex-1">{{ $atleta->apelido ?: $atleta->nome }}</span>
                                <span class="text-sm text-gray-500">#{{ $atleta->numero }}</span>
                            </li>
                        @endforeach
                    </ul>
                </div>
            </div>

            <!-- Time Branco -->
            <div class="card bg-white text-base-100 border-2 shadow-xl">
                <div class="card-body text-base-900">
                    <h3 class="card-title mb-4">
                        <img class="size-[40px] object-contain" src="/images/logo_noc_branco.png" alt="Logo noc">
                        <span>Time Branco</span>
                    </h3>
                    <ul class="space-y-2">
                        @foreach ($timeBranco as $index => $atleta)
                            <li class="flex items-center gap-2 p-2 bg-base-900 rounded">
                                <div class="avatar avatar-placeholder">
                                    <div class="bg-base-content text-base-300 rounded-full w-10">
                                        <span
                                            class="text-lg font-bold">{{ mb_strtoupper(mb_substr($atleta->apelido ?: $atleta->nome, 0, 1)) }}</span>
                                    </div>
                                </div>
                                <span class="flex-1">{{ $atleta->apelido ?: $atleta->nome }}</span>
                                <span class="text-sm text-gray-500">#{{ $atleta->numero }}</span>
                            </li>
                        @endforeach
                    </ul>
                </div>
            </div>
        </div>

        <!-- Ações Finais -->
        <div class="flex flex-col sm:flex-row gap-4">
            <button onclick="copiarMensagem()" class="btn btn-primary btn-lg flex-1">
                <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                        d="M8 16H6a2 2 0 01-2-2V6a2 2 0 012-2h8a2 2 0 012 2v2m-6 12h8a2 2 0 002-2v-8a2 2 0 00-2-2h-8a2 2 0 00-2 2v8a2 2 0 002 2z" />
                </svg>
                Copiar Mensagem
            </button>

            <a href="{{ route('partidas.show', $partida) }}" class="btn btn-ghost btn-lg flex-1">
                ← Voltar para Partida
            </a>
        </div>
    </div>
@endsection

@push('scripts')
    <script>
        function copiarMensagem() {
            const textarea = document.getElementById('mensagemTexto');
            const btnCopiar = document.getElementById('btnCopiar');
            const alerta = document.getElementById('mensagemCopiada');

            // Selecionar e copiar
            textarea.select();
            textarea.setSelectionRange(0, 99999); // Para mobile

            try {
                navigator.clipboard.writeText(textarea.value).then(function() {
                    // Feedback visual
                    btnCopiar.classList.add('btn-success');
                    btnCopiar.innerHTML = `
                <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7" />
                </svg>
                Copiado!
            `;

                    alerta.classList.remove('hidden');

                    // Restaurar após 3 segundos
                    setTimeout(() => {
                        btnCopiar.classList.remove('btn-success');
                        btnCopiar.innerHTML = `
                    <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 16H6a2 2 0 01-2-2V6a2 2 0 012-2h8a2 2 0 012 2v2m-6 12h8a2 2 0 002-2v-8a2 2 0 00-2-2h-8a2 2 0 00-2 2v8a2 2 0 002 2z" />
                    </svg>
                    Copiar Mensagem
                `;
                        alerta.classList.add('hidden');
                    }, 3000);
                });
            } catch (err) {
                // Fallback para navegadores antigos
                document.execCommand('copy');
                alert('Mensagem copiada!');
            }
        }

        // Atalho de teclado: Ctrl+C ou Cmd+C
        document.addEventListener('keydown', function(e) {
            if ((e.ctrlKey || e.metaKey) && e.key === 'c') {
                const selection = window.getSelection().toString();
                if (!selection) { // Se nada estiver selecionado, copia a mensagem
                    e.preventDefault();
                    copiarMensagem();
                }
            }
        });
    </script>
@endpush
