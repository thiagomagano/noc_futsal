<?php

namespace App\Http\Controllers;

use App\Models\Partida;
use App\Models\Atleta;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\View\View;

class PartidaController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index(Request $request): View
    {
        $query = Partida::query()->with('atletas');

        // Filtros
        if ($request->filled('status')) {
            $query->where('status', $request->status);
        }

        if ($request->filled('tipo')) {
            if ($request->tipo === 'proximas') {
                $query->proximas();
            } elseif ($request->tipo === 'passadas') {
                $query->passadas();
            }
        }

        // Ordenação padrão
        $query->orderBy('data', 'desc');

        $partidas = $query->paginate(15)->withQueryString();

        // Dados para filtros

        $filtros = [
            'status_options' => Partida::STATUS_OPTIONS,
        ];


        return view('partidas.index', compact('partidas', 'filtros'));
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create(): View
    {
        $partida = new Partida();
        $statusOptions = Partida::STATUS_OPTIONS;
        $atletasAtivos = Atleta::ativos()->orderBy('nome')->get();

        return view('partidas.create', compact('partida', 'statusOptions', 'atletasAtivos'));
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request): RedirectResponse
    {
        $validated = $request->validate([
            'data' => 'required|date|after_or_equal:today',
            'hora' => 'required|date_format:H:i',
            'local' => 'required|string|max:255',
            'observacoes' => 'nullable|string|max:1000',
            'atletas' => 'nullable|array',
            'atletas.*' => 'exists:atletas,id',
        ], [
            'data.required' => 'A data é são obrigatórias.',
            'data.date' => 'Data inválida.',
            'data.after' => 'A data da partida deve ser futura.',
            'local.required' => 'O local é obrigatório.',
            'local.max' => 'O local não pode ter mais de 255 caracteres.',
            'observacoes.max' => 'As observações não podem ter mais de 1000 caracteres.',
            'atletas.array' => 'Atletas inválidos.',
            'atletas.*.exists' => 'Um ou mais atletas selecionados não existem.',
        ]);

        try {
            $partida = Partida::create([
                'data' => $validated['data'],
                'hora' => $validated['hora'],
                'local' => $validated['local'],
                'observacoes' => $validated['observacoes'] ?? null,
                'status' => Partida::STATUS_ABERTA,
            ]);

            // Adicionar atletas selecionados (sem confirmação ainda)
            if (!empty($validated['atletas'])) {
                $atletasData = [];
                foreach ($validated['atletas'] as $atletaId) {
                    $atletasData[$atletaId] = ['confirmado' => false];
                }
                $partida->atletas()->attach($atletasData);
            }

            return redirect()
                ->route('partidas.show', $partida)
                ->with('success', 'Partida criada com sucesso!');
        } catch (\Exception $e) {
            return redirect()
                ->back()
                ->withInput()
                ->with('error', 'Erro ao criar partida. Tente novamente.');
        }
    }

    /**
     * Display the specified resource.
     */
    public function show(Partida $partida): View
    {
        $partida->load([
            'atletas' => function ($query) {
                $query->orderBy('nome');
            },
            'timePreto' => function ($query) {
                $query->orderBy('nivel_habilidade', 'desc');
            },
            'timeBranco' => function ($query) {
                $query->orderBy('nivel_habilidade', 'desc');
            }
        ]);

        $atletasDisponiveis = Atleta::ativos()
            ->whereNotIn('id', $partida->atletas->pluck('id'))
            ->orderBy('nome')
            ->get();

        $balanceamento = $partida->temTimesDefinidos()
            ? $partida->getBalanceamentoTimes()
            : null;

        return view('partidas.show', compact('partida', 'atletasDisponiveis', 'balanceamento'));
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(Partida $partida): View
    {
        // Não permitir editar se times já definidos
        if ($partida->temTimesDefinidos() || $partida->isFinalizada()) {
            return redirect()
                ->route('partidas.show', $partida)
                ->with('error', 'Não é possível editar uma partida com times definidos ou finalizada.');
        }

        $statusOptions = Partida::STATUS_OPTIONS;

        return view('partidas.edit', compact('partida', 'statusOptions'));
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, Partida $partida): RedirectResponse
    {
        // Validar se pode editar
        if ($partida->temTimesDefinidos() || $partida->isFinalizada()) {
            return redirect()
                ->route('partidas.show', $partida)
                ->with('error', 'Não é possível editar uma partida com times definidos ou finalizada.');
        }

        $validated = $request->validate([
            'data' => 'required|date',
            'local' => 'required|string|max:255',
            'observacoes' => 'nullable|string|max:1000',
            'status' => 'required|in:' . implode(',', array_keys(Partida::STATUS_OPTIONS)),
        ], [
            'data.required' => 'A data e hora são obrigatórias.',
            'data.date' => 'Data inválida.',
            'local.required' => 'O local é obrigatório.',
            'local.max' => 'O local não pode ter mais de 255 caracteres.',
            'observacoes.max' => 'As observações não podem ter mais de 1000 caracteres.',
            'status.required' => 'O status é obrigatório.',
            'status.in' => 'Status inválido.',
        ]);

        try {
            $partida->update($validated);

            return redirect()
                ->route('partidas.show', $partida)
                ->with('success', 'Partida atualizada com sucesso!');
        } catch (\Exception $e) {
            return redirect()
                ->back()
                ->withInput()
                ->with('error', 'Erro ao atualizar partida. Tente novamente.');
        }
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(Partida $partida): RedirectResponse
    {
        // Só permitir deletar se não estiver finalizada
        if ($partida->isFinalizada()) {
            return redirect()
                ->route('partidas.index')
                ->with('error', 'Não é possível deletar uma partida finalizada.');
        }

        try {
            $partida->delete();

            return redirect()
                ->route('partidas.index')
                ->with('success', 'Partida removida com sucesso!');
        } catch (\Exception $e) {
            return redirect()
                ->back()
                ->with('error', 'Erro ao remover partida. Tente novamente.');
        }
    }

    /**
     * Confirmar ou cancelar presença de um atleta
     */
    public function toggleConfirmacao(Request $request, Partida $partida, Atleta $atleta): RedirectResponse
    {
        // Verificar se atleta está na partida
        if (!$partida->atletas->contains($atleta)) {
            return redirect()
                ->back()
                ->with('error', 'Atleta não está relacionado a esta partida.');
        }

        // Não permitir alteração se times já definidos
        if ($partida->temTimesDefinidos()) {
            return redirect()
                ->back()
                ->with('error', 'Não é possível alterar confirmações após os times serem definidos.');
        }

        try {
            $confirmado = $partida->atletas()
                ->where('atleta_id', $atleta->id)
                ->first()
                ->pivot
                ->confirmado;

            // Toggle confirmação
            $partida->atletas()->updateExistingPivot($atleta->id, [
                'confirmado' => !$confirmado
            ]);

            $status = !$confirmado ? 'confirmada' : 'cancelada';

            return redirect()
                ->back()
                ->with('success', "Presença {$status} com sucesso!");
        } catch (\Exception $e) {
            return redirect()
                ->back()
                ->with('error', 'Erro ao alterar confirmação. Tente novamente.');
        }
    }

    /**
     * Adicionar atletas à partida
     */
    public function adicionarAtletas(Request $request, Partida $partida): RedirectResponse
    {
        $validated = $request->validate([
            'atletas' => 'required|array|min:1',
            'atletas.*' => 'exists:atletas,id',
        ], [
            'atletas.required' => 'Selecione pelo menos um atleta.',
            'atletas.min' => 'Selecione pelo menos um atleta.',
            'atletas.*.exists' => 'Um ou mais atletas selecionados não existem.',
        ]);

        try {
            $atletasData = [];
            foreach ($validated['atletas'] as $atletaId) {
                // Verificar se já não está na partida
                if (!$partida->atletas->contains($atletaId)) {
                    $atletasData[$atletaId] = ['confirmado' => false];
                }
            }

            if (!empty($atletasData)) {
                $partida->atletas()->attach($atletasData);
                $total = count($atletasData);

                return redirect()
                    ->back()
                    ->with('success', "{$total} atleta(s) adicionado(s) com sucesso!");
            }

            return redirect()
                ->back()
                ->with('info', 'Todos os atletas selecionados já estão na partida.');
        } catch (\Exception $e) {
            return redirect()
                ->back()
                ->with('error', 'Erro ao adicionar atletas. Tente novamente.');
        }
    }

    /**
     * Remover atleta da partida
     */
    public function removerAtleta(Partida $partida, Atleta $atleta): RedirectResponse
    {
        // Não permitir remoção se times já definidos
        if ($partida->temTimesDefinidos()) {
            return redirect()
                ->back()
                ->with('error', 'Não é possível remover atletas após os times serem definidos.');
        }

        try {
            $partida->atletas()->detach($atleta->id);

            return redirect()
                ->back()
                ->with('success', 'Atleta removido da partida com sucesso!');
        } catch (\Exception $e) {
            return redirect()
                ->back()
                ->with('error', 'Erro ao remover atleta. Tente novamente.');
        }
    }
}