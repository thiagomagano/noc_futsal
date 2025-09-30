<?php

namespace App\Http\Controllers;

use App\Http\Requests\StoreAtletaRequest;
use App\Http\Requests\UpdateAtletaRequest;
use App\Models\Atleta;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\View\View;

class AtletaController extends Controller
{
    public function index(Request $request): View
    {
        $query = Atleta::query();

        // Filtros
        if ($request->filled('search')) {
            $query->buscar($request->search);
        }

        if ($request->filled('posicao')) {
            $query->porPosicao($request->posicao);
        }

        if ($request->filled('nivel')) {
            $query->porNivel($request->nivel);
        }

        if ($request->filled('status')) {
            if ($request->status === 'ativo') {
                $query->ativos();
            } elseif ($request->status === 'inativo') {
                $query->inativos();
            }
        }

        // Ordenação
        $sortBy = $request->get('sort', 'nome');
        $sortDirection = $request->get('direction', 'asc');

        $allowedSorts = ['nome', 'posicao', 'nivel_habilidade', 'status', 'created_at'];
        if (in_array($sortBy, $allowedSorts)) {
            $query->orderBy($sortBy, $sortDirection);
        }

        $atletas = $query->paginate(15)->withQueryString();

        // Dados para filtros
        $filtros = [
            'posicoes' => Atleta::POSICOES,
            'niveis' => Atleta::NIVEIS_HABILIDADE,
            'status_options' => Atleta::STATUS_OPTIONS,
        ];

        return view('atletas.index', compact('atletas', 'filtros'));
    }

    public function create(): View
    {
        $atleta = new Atleta;
        $posicoes = Atleta::POSICOES;
        $statusOptions = Atleta::STATUS_OPTIONS;

        return view('atletas.create', compact('atleta', 'posicoes', 'statusOptions'));
    }

    public function store(StoreAtletaRequest $request): RedirectResponse
    {
        try {
            $atleta = Atleta::create($request->validated());

            return redirect()
                ->route('atletas.show', $atleta)
                ->with('success', 'Atleta cadastrado com sucesso!');
        } catch (\Exception $e) {
            return redirect()
                ->back()
                ->withInput()
                ->with('error', 'Erro ao cadastrar atleta. Tente novamente.');
        }
    }

    public function show(Atleta $atleta): View
    {
        return view('atletas.show', compact('atleta'));
    }

    public function edit(Atleta $atleta): View
    {
        $posicoes = Atleta::POSICOES;
        $statusOptions = Atleta::STATUS_OPTIONS;

        return view('atletas.edit', compact('atleta', 'posicoes', 'statusOptions'));
    }

    public function update(UpdateAtletaRequest $request, Atleta $atleta): RedirectResponse
    {
        try {
            $atleta->update($request->validated());

            return redirect()
                ->route('atletas.show', $atleta)
                ->with('success', 'Atleta atualizado com sucesso!');
        } catch (\Exception $e) {
            return redirect()
                ->back()
                ->withInput()
                ->with('error', 'Erro ao atualizar atleta. Tente novamente.');
        }
    }

    public function destroy(Atleta $atleta): RedirectResponse
    {
        try {
            $atleta->delete();

            return redirect()
                ->route('atletas.index')
                ->with('success', 'Atleta removido com sucesso!');
        } catch (\Exception $e) {
            return redirect()
                ->back()
                ->with('error', 'Erro ao remover atleta. Tente novamente.');
        }
    }

    public function toggleStatus(Atleta $atleta): RedirectResponse
    {
        try {
            $atleta->toggleStatus();

            $status = $atleta->isAtivo() ? 'ativado' : 'inativado';

            return redirect()
                ->back()
                ->with('success', "Atleta {$status} com sucesso!");
        } catch (\Exception $e) {
            return redirect()
                ->back()
                ->with('error', 'Erro ao alterar status do atleta.');
        }
    }

    public function restore(int $id): RedirectResponse
    {
        try {
            $atleta = Atleta::withTrashed()->findOrFail($id);
            $atleta->restore();

            return redirect()
                ->route('atletas.show', $atleta)
                ->with('success', 'Atleta restaurado com sucesso!');
        } catch (\Exception $e) {
            return redirect()
                ->back()
                ->with('error', 'Erro ao restaurar atleta.');
        }
    }
}
