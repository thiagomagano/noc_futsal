<?php

namespace App\Http\Controllers;

use App\Models\Atleta;
use Illuminate\Http\Request;
use Illuminate\View\View;

class DashboardController extends Controller
{
    public function index(): View
    {
        // Estatísticas básicas
        $totalAtletas = Atleta::count();
        $atletasAtivos = Atleta::ativos()->count();
        $atletasInativos = Atleta::inativos()->count();
        $nivelMedio = Atleta::ativos()->avg('nivel_habilidade');

        // Distribuição por posição
        $distribuicaoPosicoes = Atleta::ativos()
            ->selectRaw('posicao, COUNT(*) as total')
            ->groupBy('posicao')
            ->pluck('total', 'posicao')
            ->toArray();

        // Distribuição por nível
        $distribuicaoNiveis = Atleta::ativos()
            ->selectRaw('nivel_habilidade, COUNT(*) as total')
            ->groupBy('nivel_habilidade')
            ->orderBy('nivel_habilidade')
            ->pluck('total', 'nivel_habilidade')
            ->toArray();

        // Últimos atletas cadastrados
        $ultimosAtletas = Atleta::latest()
            ->limit(5)
            ->get();

        // Atletas por status para o gráfico
        $statusStats = [
            'ativos' => $atletasAtivos,
            'inativos' => $atletasInativos,
        ];

        return view('dashboard', compact(
            'totalAtletas',
            'atletasAtivos',
            'atletasInativos',
            'nivelMedio',
            'distribuicaoPosicoes',
            'distribuicaoNiveis',
            'ultimosAtletas',
            'statusStats'
        ));
    }
}