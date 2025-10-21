<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class Atleta extends Model
{
    use HasFactory, SoftDeletes;

    protected $fillable = [
        'nome',
        'apelido',
        'numero',
        'posicao',
        'nivel_habilidade',
        'telefone',
        'status',
        'observacoes',
    ];

    protected $casts = [
        'nivel_habilidade' => 'integer',
    ];

    // Constantes para enums
    const POSICOES = [
        'goleiro' => 'Goleiro',
        'linha' => 'Linha',
    ];

    const STATUS_OPTIONS = [
        'ativo' => 'Ativo',
        'inativo' => 'Inativo',
    ];

    const NIVEIS_HABILIDADE = [
        1 => 'Bagre',
        2 => 'Tenta',
        3 => 'Mediano',
        4 => 'Bom de Bola',
        5 => 'Crack',
    ];

    // Accessors
    public function getNomeCompletoAttribute(): string
    {
        return $this->apelido ? "{$this->nome} ({$this->apelido})" : $this->nome;
    }

    public function getPosicaoFormatadaAttribute(): string
    {
        return self::POSICOES[$this->posicao] ?? $this->posicao;
    }

    public function getNivelDescricaoAttribute(): string
    {
        return self::NIVEIS_HABILIDADE[$this->nivel_habilidade] ?? 'N/A';
    }

    public function getStatusFormatadoAttribute(): string
    {
        return self::STATUS_OPTIONS[$this->status] ?? $this->status;
    }

    // Scopes
    public function scopeAtivos(Builder $query): Builder
    {
        return $query->where('status', 'ativo');
    }

    public function scopeInativos(Builder $query): Builder
    {
        return $query->where('status', 'inativo');
    }

    public function scopePorPosicao(Builder $query, string $posicao): Builder
    {
        return $query->where('posicao', $posicao);
    }

    public function scopePorNivel(Builder $query, int $nivel): Builder
    {
        return $query->where('nivel_habilidade', $nivel);
    }
    public function scopeBuscar(Builder $query, string $termo): Builder
    {
        return $query->where(function ($q) use ($termo) {
            $q->where('nome', 'like', "%{$termo}%")
                ->orWhere('apelido', 'like', "%{$termo}%")
                ->orWhere('telefone', 'like', "%{$termo}%");
        });
    }

    // Métodos utilitários
    public function isAtivo(): bool
    {
        return $this->status === 'ativo';
    }

    public function toggleStatus(): void
    {
        $this->status = $this->isAtivo() ? 'inativo' : 'ativo';
        $this->save();
    }

    public function getEstrelas(): array
    {
        $estrelas = [];
        for ($i = 1; $i <= 5; $i++) {
            $estrelas[] = $i <= $this->nivel_habilidade;
        }

        return $estrelas;
    }
}
