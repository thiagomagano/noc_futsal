@extends('layouts.app')

@section('title', 'Story Instagram - Futsal Manager')

@section('breadcrumb')
    <li><a href="{{ route('dashboard') }}">Dashboard</a></li>
    <li><a href="{{ route('partidas.index') }}">Partidas</a></li>
    <li><a href="{{ route('partidas.show', $partida) }}">{{ $partida->data_formatada }}</a></li>
    <li>Story Instagram</li>
@endsection

@section('header')
    <div class="flex flex-col lg:flex-row lg:items-center lg:justify-between gap-4">
        <div>
            <h1 class="text-3xl font-bold text-gray-900">📸 Story para Instagram</h1>
            <p class="text-gray-600 mt-1">Gere uma imagem com a escalação dos times</p>
        </div>
    </div>
@endsection

@section('content')
    <div class="max-w-6xl mx-auto space-y-6">
        <!-- Alertas -->
        <div class="alert alert-info">
            <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24"
                class="stroke-current shrink-0 w-6 h-6">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                    d="M13 16h-1v-4h-1m1-4h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z"></path>
            </svg>
            <div>
                <h3 class="font-bold">Como usar</h3>
                <div class="text-sm">
                    Clique em "Gerar Imagem" para criar o story. Depois clique em "Baixar Imagem" e publique no Instagram!
                </div>
            </div>
        </div>

        <div class="grid grid-cols-1 lg:grid-cols-2 gap-6">
            <!-- Preview do Story -->
            <div class="space-y-4">
                <div class="card bg-base-100 shadow-xl">
                    <div class="card-body">
                        <h2 class="card-title mb-4">📱 Preview do Story</h2>

                        <!-- Container do Story (1080x1920 = 9:16) -->
                        <div class="flex justify-center">
                            <div class="relative bg-gray-200 rounded-lg overflow-hidden shadow-2xl"
                                style="width: 360px; height: 640px;">
                                <div id="storyContent"
                                    class="w-full h-full bg-primary p-8 text-white flex flex-col justify-between">

                                    <!-- Header -->
                                    <div class="text-center">
                                        <div class="text-4xl font-black mb-2">⚽ FUTSAL</div>
                                        <div class="text-xl font-bold mb-4">ESCALAÇÃO</div>
                                        <div class="bg-white/20 backdrop-blur-sm rounded-lg p-3 text-sm">
                                            <div class="font-semibold">📅 {{ $partida->data_formatada }}</div>
                                            <div>🕐 {{ $partida->hora_formatada }}</div>
                                            <div class="text-xs mt-1">📍 {{ Str::limit($partida->local, 30) }}</div>
                                        </div>
                                    </div>

                                    <!-- Times -->
                                    <div class="space-y-4 flex-1 flex flex-col justify-center">
                                        <!-- Time Preto -->
                                        <div class="bg-black/50 backdrop-blur-sm rounded-xl p-4">
                                            <div class="flex items-center justify-between mb-3">
                                                <h3 class="text-lg font-bold flex items-center gap-2">
                                                    <span class="text-2xl">⚫</span>
                                                    TIME PRETO
                                                </h3>
                                                <span class="text-xs bg-white/20 px-2 py-1 rounded">
                                                    {{ $timePreto->count() }} jogadores
                                                </span>
                                            </div>
                                            <div class="space-y-1 text-sm">
                                                @foreach ($timePreto as $index => $atleta)
                                                    <div class="flex items-center justify-between">
                                                        <span>{{ $index + 1 }}.
                                                            {{ $atleta->apelido ?: Str::limit($atleta->nome, 15) }}</span>
                                                        <span
                                                            class="text-yellow-300">{{ str_repeat('⭐', min($atleta->nivel_habilidade, 3)) }}</span>
                                                    </div>
                                                @endforeach
                                            </div>
                                        </div>

                                        <!-- VS -->
                                        <div class="text-center">
                                            <div
                                                class="inline-block bg-white text-purple-600 font-black text-xl px-4 py-2 rounded-full shadow-lg">
                                                VS
                                            </div>
                                        </div>

                                        <!-- Time Branco -->
                                        <div class="bg-white/90 backdrop-blur-sm rounded-xl p-4 text-gray-800">
                                            <div class="flex items-center justify-between mb-3">
                                                <h3 class="text-lg font-bold flex items-center gap-2">
                                                    <span class="text-2xl">⚪</span>
                                                    TIME BRANCO
                                                </h3>
                                                <span class="text-xs bg-purple-600 text-white px-2 py-1 rounded">
                                                    {{ $timeBranco->count() }} jogadores
                                                </span>
                                            </div>
                                            <div class="space-y-1 text-sm">
                                                @foreach ($timeBranco as $index => $atleta)
                                                    <div class="flex items-center justify-between">
                                                        <span>{{ $index + 1 }}.
                                                            {{ $atleta->apelido ?: Str::limit($atleta->nome, 15) }}</span>
                                                        <span
                                                            class="text-orange-500">{{ str_repeat('⭐', min($atleta->nivel_habilidade, 3)) }}</span>
                                                    </div>
                                                @endforeach
                                            </div>
                                        </div>
                                    </div>

                                    <!-- Footer -->
                                    <div class="text-center">
                                        <div class="bg-white/20 backdrop-blur-sm rounded-lg p-3">
                                            <div class="text-xs font-semibold">#FUTSAL #PELADA #RACHA</div>
                                            <div class="text-xs mt-1">Bom jogo a todos! 🔥</div>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>

            <!-- Controles e Informações -->
            <div class="space-y-6">
                <!-- Botões de Ação -->
                <div class="card bg-primary text-primary-content shadow-xl">
                    <div class="card-body">
                        <h2 class="card-title mb-4">🎨 Gerar Imagem</h2>

                        <div class="space-y-4">
                            <button onclick="gerarImagem()" id="btnGerar" class="btn btn-accent btn-lg w-full">
                                <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                        d="M4 16l4.586-4.586a2 2 0 012.828 0L16 16m-2-2l1.586-1.586a2 2 0 012.828 0L20 14m-6-6h.01M6 20h12a2 2 0 002-2V6a2 2 0 00-2-2H6a2 2 0 00-2 2v12a2 2 0 002 2z" />
                                </svg>
                                Gerar Imagem
                            </button>

                            <button onclick="baixarImagem()" id="btnBaixar" class="btn btn-success btn-lg w-full hidden">
                                <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                        d="M4 16v1a3 3 0 003 3h10a3 3 0 003-3v-1m-4-4l-4 4m0 0l-4-4m4 4V4" />
                                </svg>
                                Baixar Imagem
                            </button>

                            <div id="statusGeracao" class="hidden">
                                <div class="alert alert-success">
                                    <svg xmlns="http://www.w3.org/2000/svg" class="stroke-current shrink-0 h-6 w-6"
                                        fill="none" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                            d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z" />
                                    </svg>
                                    <span>Imagem gerada com sucesso!</span>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>

                <!-- Informações -->
                <div class="card bg-base-100 shadow-xl">
                    <div class="card-body">
                        <h3 class="card-title mb-4">ℹ️ Informações</h3>

                        <div class="space-y-3 text-sm">
                            <div>
                                <strong>Dimensões:</strong> 1080x1920px (9:16)
                            </div>
                            <div>
                                <strong>Formato:</strong> PNG
                            </div>
                            <div>
                                <strong>Ideal para:</strong> Instagram Stories
                            </div>
                        </div>

                        <div class="divider"></div>

                        <div class="space-y-2 text-sm">
                            <p class="font-semibold">📱 Como publicar:</p>
                            <ol class="list-decimal list-inside space-y-1 text-xs">
                                <li>Clique em "Gerar Imagem"</li>
                                <li>Clique em "Baixar Imagem"</li>
                                <li>Abra o Instagram</li>
                                <li>Toque em "Sua História"</li>
                                <li>Selecione a imagem baixada</li>
                                <li>Publique!</li>
                            </ol>
                        </div>
                    </div>
                </div>

                <!-- Estatísticas -->
                <div class="card bg-base-100 shadow-xl">
                    <div class="card-body">
                        <h3 class="card-title mb-4">📊 Estatísticas dos Times</h3>

                        <div class="space-y-3">
                            <div class="flex justify-between items-center p-2 bg-base-200 rounded">
                                <span class="text-sm">⚫ Time Preto</span>
                                <span class="font-bold">{{ $balanceamento['preto']['total_jogadores'] }} jogadores</span>
                            </div>

                            <div class="flex justify-between items-center p-2 bg-base-200 rounded">
                                <span class="text-sm">⚪ Time Branco</span>
                                <span class="font-bold">{{ $balanceamento['branco']['total_jogadores'] }} jogadores</span>
                            </div>

                            <div class="divider my-2"></div>

                            <div class="flex justify-between items-center p-2">
                                <span class="text-sm">Média Preto</span>
                                <span class="font-bold text-primary">{{ $balanceamento['preto']['media_habilidade'] }}
                                    ⭐</span>
                            </div>

                            <div class="flex justify-between items-center p-2">
                                <span class="text-sm">Média Branco</span>
                                <span class="font-bold text-primary">{{ $balanceamento['branco']['media_habilidade'] }}
                                    ⭐</span>
                            </div>
                        </div>
                    </div>
                </div>

                <!-- Voltar -->
                <a href="{{ route('partidas.show', $partida) }}" class="btn btn-ghost btn-lg w-full">
                    ← Voltar para Partida
                </a>
            </div>
        </div>

        <!-- Canvas oculto para geração da imagem em alta resolução -->
        <canvas id="canvasHidden" width="1080" height="1920" class="hidden"></canvas>
    </div>
@endsection

@push('scripts')
    <script>
        import * as htmlToImage from 'html-to-image';

        let imagemGerada = null;

        async function gerarImagem() {
            const btnGerar = document.getElementById('btnGerar');
            const btnBaixar = document.getElementById('btnBaixar');
            const status = document.getElementById('statusGeracao');
            const storyContent = document.getElementById('storyContent');

            // Mostrar loading
            btnGerar.innerHTML = `
        <span class="loading loading-spinner"></span>
        Gerando...
        `;
            btnGerar.disabled = true;

            try {
                htmlToImage
                    .toPng(node)
                    .then((dataUrl) => {
                        const img = new Image();
                        img.src = dataUrl;
                        document.body.appendChild(img);
                    })

                // Salvar imagem gerada
                imagemGerada = canvas.toDataURL('image/png');

                // Mostrar sucesso
                status.classList.remove('hidden');
                btnBaixar.classList.remove('hidden');

                // Restaurar botão
                btnGerar.innerHTML = `
            <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z" />
            </svg>
            Imagem Gerada!
        `;
                btnGerar.classList.add('btn-success');

            } catch (error) {
                console.error('Erro ao gerar imagem:', error);
                alert('Erro ao gerar imagem. Tente novamente.');

                btnGerar.innerHTML = `
            <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 16l4.586-4.586a2 2 0 012.828 0L16 16m-2-2l1.586-1.586a2 2 0 012.828 0L20 14m-6-6h.01M6 20h12a2 2 0 002-2V6a2 2 0 00-2-2H6a2 2 0 00-2 2v12a2 2 0 002 2z" />
            </svg>
            Gerar Imagem
        `;
                btnGerar.disabled = false;
            }
        }

        function baixarImagem() {
            if (!imagemGerada) {
                alert('Gere a imagem primeiro!');
                return;
            }

            // Criar link de download
            const link = document.createElement('a');
            link.download = 'escalacao-futsal-{{ $partida->data->format('Y-m-d') }}.png';
            link.href = imagemGerada;
            link.click();
        }
    </script>
@endpush
