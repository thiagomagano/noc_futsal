<?php

namespace App\Http\Controllers;

use App\Models\Partida;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\View\View;

class DivisaoTimesController extends Controller
{
    /**
     * Mostrar preview da divisão de times
     */
    public function preview(Partida $partida): View
    {
        // Validar se pode definir times
        if (!$partida->podeDefinirTimes()) {
            $goleirosConfirmados = $partida->atletasConfirmados()
                ->where('posicao', 'goleiro')
                ->count();
                
            if ($goleirosConfirmados < 2) {
                $mensagem = 'É necessário pelo menos 2 goleiros confirmados para definir times.';
            } else {
                $mensagem = 'Partida não tem quórum suficiente para definir times (10-14 jogadores confirmados).';
            }
            
            return redirect()
                ->route('partidas.show', $partida)
                ->with('error', $mensagem);
        }

        // Executar algoritmo de divisão
        $divisao = $this->executarAlgoritmoDivisao($partida);

        return view('partidas.divisao-preview', compact('partida', 'divisao'));
    }

    /**
     * Confirmar e salvar a divisão de times
     */
    public function confirmar(Partida $partida): RedirectResponse
    {
        // Validar se pode definir times
        if (!$partida->podeDefinirTimes()) {
            $goleirosConfirmados = $partida->atletasConfirmados()
                ->where('posicao', 'goleiro')
                ->count();
                
            if ($goleirosConfirmados < 2) {
                $mensagem = 'É necessário pelo menos 2 goleiros confirmados para definir times.';
            } else {
                $mensagem = 'Partida não tem quórum suficiente para definir times (10-14 jogadores confirmados).';
            }
            
            return redirect()
                ->route('partidas.show', $partida)
                ->with('error', $mensagem);
        }

        try {
            // Executar algoritmo de divisão
            $divisao = $this->executarAlgoritmoDivisao($partida);

            // Salvar divisão no banco
            foreach ($divisao['time_preto'] as $atleta) {
                $partida->atletas()->updateExistingPivot($atleta->id, [
                    'time' => Partida::TIME_PRETO
                ]);
            }

            foreach ($divisao['time_branco'] as $atleta) {
                $partida->atletas()->updateExistingPivot($atleta->id, [
                    'time' => Partida::TIME_BRANCO
                ]);
            }

            // Atualizar status da partida
            $partida->update([
                'status' => Partida::STATUS_TIMES_DEFINIDOS
            ]);

            return redirect()
                ->route('partidas.show', $partida)
                ->with('success', 'Times definidos com sucesso!');
        } catch (\Exception $e) {
            return redirect()
                ->back()
                ->with('error', 'Erro ao definir times. Tente novamente.');
        }
    }

    /**
     * Redistribuir times (limpar e gerar novamente)
     */
    public function redistribuir(Partida $partida): RedirectResponse
    {
        try {
            // Limpar times atuais
            $partida->atletas()->update([
                'time' => null
            ]);

            // Voltar status para confirmada
            $partida->update([
                'status' => Partida::STATUS_CONFIRMADA
            ]);

            return redirect()
                ->route('partidas.divisao.preview', $partida)
                ->with('success', 'Times redistribuídos! Confira a nova divisão.');
        } catch (\Exception $e) {
            return redirect()
                ->back()
                ->with('error', 'Erro ao redistribuir times. Tente novamente.');
        }
    }

    /**
     * Gerar mensagem formatada para WhatsApp
     */
    public function gerarMensagem(Partida $partida): View
    {
        if (!$partida->temTimesDefinidos()) {
            return redirect()
                ->route('partidas.show', $partida)
                ->with('error', 'Os times ainda não foram definidos.');
        }

        $timePreto = $partida->timePreto()
            ->orderBy('nivel_habilidade', 'desc')
            ->get();

        $timeBranco = $partida->timeBranco()
            ->orderBy('nivel_habilidade', 'desc')
            ->get();

        $balanceamento = $partida->getBalanceamentoTimes();

        // Gerar mensagem formatada
        $mensagem = $this->formatarMensagemWhatsApp($partida, $timePreto, $timeBranco, $balanceamento);

        return view('partidas.mensagem-whatsapp', compact('partida', 'mensagem', 'timePreto', 'timeBranco', 'balanceamento'));
    }

    /**
     * Algoritmo de divisão de times balanceado
     * Equilibra quantidade de jogadores, habilidade total e goleiros
     */
    private function executarAlgoritmoDivisao(Partida $partida): array
    {
        // Buscar atletas confirmados ordenados por nível de habilidade (DESC)
        $atletasConfirmados = $partida->atletasConfirmados()
            ->orderBy('nivel_habilidade', 'desc')
            ->orderBy('nome', 'asc')
            ->get();

        $totalJogadores = $atletasConfirmados->count();
        
        // Separar goleiros dos demais jogadores
        $goleiros = $atletasConfirmados->where('posicao', 'goleiro')->values();
        $jogadoresLinha = $atletasConfirmados->where('posicao', 'linha')->values();
        
        // Validar se há goleiros suficientes
        if ($goleiros->count() < 2) {
            throw new \Exception('É necessário pelo menos 2 goleiros confirmados para definir times.');
        }
        
        // Calcular distribuição ideal de jogadores
        $distribuicao = $this->calcularDistribuicaoJogadores($totalJogadores);
        
        // Primeira fase: distribuir goleiros
        $timePreto = collect();
        $timeBranco = collect();
        
        // Distribuir os 2 melhores goleiros (um para cada time)
        $timePreto->push($goleiros->shift()); // Melhor goleiro para time preto
        $timeBranco->push($goleiros->shift()); // Segundo melhor goleiro para time branco
        
        // Adicionar goleiros restantes à lista de jogadores de linha
        $jogadoresLinha = $jogadoresLinha->merge($goleiros);
        
        // Segunda fase: distribuir jogadores de linha
        $turnoPreto = true;
        $jogadoresDistribuidos = 2; // Já distribuímos 2 goleiros
        
        while ($jogadoresDistribuidos < $totalJogadores) {
            if ($turnoPreto && $timePreto->count() < $distribuicao['preto']) {
                $timePreto->push($jogadoresLinha->shift());
                $turnoPreto = false;
            } elseif (!$turnoPreto && $timeBranco->count() < $distribuicao['branco']) {
                $timeBranco->push($jogadoresLinha->shift());
                $turnoPreto = true;
            } else {
                // Se um time já está completo, adicionar ao outro
                if ($timePreto->count() < $distribuicao['preto']) {
                    $timePreto->push($jogadoresLinha->shift());
                } else {
                    $timeBranco->push($jogadoresLinha->shift());
                }
            }
            $jogadoresDistribuidos++;
        }
        
        // Terceira fase: otimizar balanceamento de habilidade (mantendo goleiros)
        $melhorDivisao = $this->otimizarBalanceamentoComGoleiros($timePreto, $timeBranco, $distribuicao);
        
        // Calcular estatísticas finais
        $somaPreto = $melhorDivisao['preto']->sum('nivel_habilidade');
        $somaBranco = $melhorDivisao['branco']->sum('nivel_habilidade');
        $mediaPreto = $melhorDivisao['preto']->count() > 0 ? round($somaPreto / $melhorDivisao['preto']->count(), 2) : 0;
        $mediaBranco = $melhorDivisao['branco']->count() > 0 ? round($somaBranco / $melhorDivisao['branco']->count(), 2) : 0;

        return [
            'time_preto' => $melhorDivisao['preto'],
            'time_branco' => $melhorDivisao['branco'],
            'estatisticas' => [
                'preto' => [
                    'total' => $melhorDivisao['preto']->count(),
                    'soma' => $somaPreto,
                    'media' => $mediaPreto,
                ],
                'branco' => [
                    'total' => $melhorDivisao['branco']->count(),
                    'soma' => $somaBranco,
                    'media' => $mediaBranco,
                ],
                'diferenca_soma' => abs($somaPreto - $somaBranco),
                'diferenca_media' => abs($mediaPreto - $mediaBranco),
            ]
        ];
    }

    /**
     * Calcula a distribuição ideal de jogadores por time
     */
    private function calcularDistribuicaoJogadores(int $totalJogadores): array
    {
        // Distribuições permitidas: 6x6, 7x7, 5x5, 6x5, 7x6
        $distribuicoes = [
            10 => ['preto' => 5, 'branco' => 5],
            11 => ['preto' => 6, 'branco' => 5],
            12 => ['preto' => 6, 'branco' => 6],
            13 => ['preto' => 7, 'branco' => 6],
            14 => ['preto' => 7, 'branco' => 7],
        ];

        return $distribuicoes[$totalJogadores] ?? ['preto' => 5, 'branco' => 5];
    }

    /**
     * Otimiza o balanceamento tentando trocar jogadores entre times
     * Mantém pelo menos 1 goleiro em cada time
     */
    private function otimizarBalanceamentoComGoleiros($timePreto, $timeBranco, $distribuicao): array
    {
        $melhorPreto = $timePreto;
        $melhorBranco = $timeBranco;
        $melhorDiferenca = abs($timePreto->sum('nivel_habilidade') - $timeBranco->sum('nivel_habilidade'));
        
        // Tentar trocas para melhorar o balanceamento
        $tentativas = 0;
        $maxTentativas = 50; // Limite para evitar loop infinito
        
        while ($tentativas < $maxTentativas) {
            $melhorou = false;
            
            // Tentar trocar cada jogador do time preto com cada do branco
            foreach ($timePreto as $indexPreto => $jogadorPreto) {
                foreach ($timeBranco as $indexBranco => $jogadorBranco) {
                    // Verificar se a troca é válida (mantém pelo menos 1 goleiro por time)
                    if (!$this->validarTrocaGoleiros($timePreto, $timeBranco, $indexPreto, $indexBranco)) {
                        continue;
                    }
                    
                    // Simular troca
                    $novoPreto = $timePreto->except([$indexPreto])->push($jogadorBranco);
                    $novoBranco = $timeBranco->except([$indexBranco])->push($jogadorPreto);
                    
                    $novaDiferenca = abs($novoPreto->sum('nivel_habilidade') - $novoBranco->sum('nivel_habilidade'));
                    
                    // Se melhorou o balanceamento, aceitar a troca
                    if ($novaDiferenca < $melhorDiferenca) {
                        $melhorPreto = $novoPreto;
                        $melhorBranco = $novoBranco;
                        $melhorDiferenca = $novaDiferenca;
                        $timePreto = $novoPreto;
                        $timeBranco = $novoBranco;
                        $melhorou = true;
                        break 2; // Sair dos dois loops
                    }
                }
            }
            
            // Se não melhorou, parar
            if (!$melhorou) {
                break;
            }
            
            $tentativas++;
        }
        
        return [
            'preto' => $melhorPreto,
            'branco' => $melhorBranco
        ];
    }

    /**
     * Valida se uma troca mantém pelo menos 1 goleiro em cada time
     */
    private function validarTrocaGoleiros($timePreto, $timeBranco, $indexPreto, $indexBranco): bool
    {
        $jogadorPreto = $timePreto[$indexPreto];
        $jogadorBranco = $timeBranco[$indexBranco];
        
        // Contar goleiros antes da troca
        $goleirosPretoAntes = $timePreto->where('posicao', 'goleiro')->count();
        $goleirosBrancoAntes = $timeBranco->where('posicao', 'goleiro')->count();
        
        // Simular a troca
        $novoPreto = $timePreto->except([$indexPreto])->push($jogadorBranco);
        $novoBranco = $timeBranco->except([$indexBranco])->push($jogadorPreto);
        
        // Contar goleiros após a troca
        $goleirosPretoDepois = $novoPreto->where('posicao', 'goleiro')->count();
        $goleirosBrancoDepois = $novoBranco->where('posicao', 'goleiro')->count();
        
        // Validar se cada time mantém pelo menos 1 goleiro
        return $goleirosPretoDepois >= 1 && $goleirosBrancoDepois >= 1;
    }

    /**
     * Formatar mensagem para WhatsApp
     */
    private function formatarMensagemWhatsApp($partida, $timePreto, $timeBranco, $balanceamento): string
    {
        $mensagem = "🏆 PARTIDA CONFIRMADA 🏆\n";
        $mensagem .= "━━━━━━━━━━━━━━━━━━━━\n\n";
        $mensagem .= "📅 Data: {$partida->data_formatada}\n";
        $mensagem .= "🕐 Horário: {$partida->hora_formatada}\n";
        $mensagem .= "📍 Local: {$partida->local}\n\n";
        $mensagem .= "━━━━━━━━━━━━━━━━━━━━\n\n";

        // Time Preto
        $mensagem .= "⚫ TIME PRETO ({$balanceamento['preto']['total_jogadores']} jogadores)\n";
        $mensagem .= "━━━━━━━━━━━━━━━━\n";

        // Separar goleiros dos demais jogadores
        $goleirosPreto = [];
        $jogadoresPreto = [];
        
        foreach ($timePreto as $atleta) {
            if ($atleta->posicao === 'goleiro') {
                $goleirosPreto[] = $atleta;
            } else {
                $jogadoresPreto[] = $atleta;
            }
        }

        // Listar goleiros primeiro
        foreach ($goleirosPreto as $index => $atleta) {
            $numero = $index + 1;
            $nome = $atleta->apelido ?: $atleta->nome;
            $mensagem .= "{$numero}. {$nome} GK\n";
        }

        // Listar demais jogadores
        foreach ($jogadoresPreto as $index => $atleta) {
            $numero = count($goleirosPreto) + $index + 1;
            $nome = $atleta->apelido ?: $atleta->nome;
            $mensagem .= "{$numero}. {$nome}\n";
        }

        $mensagem .= "\n";

        // Time Branco
        $mensagem .= "⚪ TIME BRANCO ({$balanceamento['branco']['total_jogadores']} jogadores)\n";
        $mensagem .= "━━━━━━━━━━━━━━━━\n";

        // Separar goleiros dos demais jogadores
        $goleirosBranco = [];
        $jogadoresBranco = [];
        
        foreach ($timeBranco as $atleta) {
            if ($atleta->posicao === 'goleiro') {
                $goleirosBranco[] = $atleta;
            } else {
                $jogadoresBranco[] = $atleta;
            }
        }

        // Listar goleiros primeiro
        foreach ($goleirosBranco as $index => $atleta) {
            $numero = $index + 1;
            $nome = $atleta->apelido ?: $atleta->nome;
            $mensagem .= "{$numero}. {$nome} GK\n";
        }

        // Listar demais jogadores
        foreach ($jogadoresBranco as $index => $atleta) {
            $numero = count($goleirosBranco) + $index + 1;
            $nome = $atleta->apelido ?: $atleta->nome;
            $mensagem .= "{$numero}. {$nome}\n";
        }

        if ($partida->observacoes) {
            $mensagem .= "\n━━━━━━━━━━━━━━━━━━━━\n";
            $mensagem .= "📝 Obs: {$partida->observacoes}\n";
        }

        $mensagem .= "\n⚽ Bom jogo a todos! ⚽";

        return $mensagem;
    }
}