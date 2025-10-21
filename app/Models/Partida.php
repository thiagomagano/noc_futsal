<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsToMany;
use Illuminate\Database\Eloquent\Builder;
use Carbon\Carbon;

class Partida extends Model
{
    use HasFactory;

    protected $fillable = [
        'data',
        'hora',
        'local',
        'status',
        'observacoes',
    ];

    protected $casts = [
        'data' => 'datetime',
        'hora' => 'datetime:H:i:',
    ];

    // Constantes para status
    public const STATUS_ABERTA = 'aberta';
    public const STATUS_CONFIRMADA = 'confirmada';
    public const STATUS_TIMES_DEFINIDOS = 'times_definidos';
    public const STATUS_FINALIZADA = 'finalizada';
    public const STATUS_CANCELADA = 'cancelada';

    public const STATUS_OPTIONS = [
        self::STATUS_ABERTA => 'Aberta',
        self::STATUS_CONFIRMADA => 'Confirmada',
        self::STATUS_TIMES_DEFINIDOS => 'Times Definidos',
        self::STATUS_FINALIZADA => 'Finalizada',
        self::STATUS_CANCELADA => 'Cancelada',
    ];

    // Constantes para times
    public const TIME_PRETO = 'preto';
    public const TIME_BRANCO = 'branco';

    public const TIMES = [
        self::TIME_PRETO => 'Time Preto',
        self::TIME_BRANCO => 'Time Branco',
    ];

    // Limites de jogadores
    public const MIN_JOGADORES = 10;
    public const MAX_JOGADORES = 14;

    /**
     * Relacionamento: Atletas da partida (todos)
     */
    public function atletas(): BelongsToMany
    {
        return $this->belongsToMany(Atleta::class, 'partida_atletas')
            ->withPivot(['confirmado', 'time'])
            ->withTimestamps();
    }

    /**
     * Relacionamento: Apenas atletas confirmados
     */
    public function atletasConfirmados(): BelongsToMany
    {
        return $this->atletas()->wherePivot('confirmado', true);
    }

    /**
     * Relacionamento: Atletas do time preto
     */
    public function timePreto(): BelongsToMany
    {
        return $this->atletasConfirmados()->wherePivot('time', self::TIME_PRETO);
    }

    /**
     * Relacionamento: Atletas do time branco
     */
    public function timeBranco(): BelongsToMany
    {
        return $this->atletasConfirmados()->wherePivot('time', self::TIME_BRANCO);
    }

    /**
     * Accessors
     */
    public function getStatusFormatadoAttribute(): string
    {
        return self::STATUS_OPTIONS[$this->status] ?? $this->status;
    }

    public function getDataFormatadaAttribute(): string
    {
        return $this->data->format('d/m/Y');
    }

    public function getHoraFormatadaAttribute(): string
    {
        return $this->hora->format('H:i');
    }

    /**
     * Scopes
     */
    public function scopeAbertas(Builder $query): Builder
    {
        return $query->where('status', self::STATUS_ABERTA);
    }

    public function scopeConfirmadas(Builder $query): Builder
    {
        return $query->where('status', self::STATUS_CONFIRMADA);
    }

    public function scopeTimesDefinidos(Builder $query): Builder
    {
        return $query->where('status', self::STATUS_TIMES_DEFINIDOS);
    }

    public function scopeFinalizadas(Builder $query): Builder
    {
        return $query->where('status', self::STATUS_FINALIZADA);
    }

    public function scopeCanceladas(Builder $query): Builder
    {
        return $query->where('status', self::STATUS_CANCELADA);
    }

    public function scopeProximas(Builder $query): Builder
    {
        return $query->where('data', '>=', now())
            ->whereNotIn('status', [self::STATUS_FINALIZADA, self::STATUS_CANCELADA])
            ->orderBy('data', 'asc');
    }

    public function scopePassadas(Builder $query): Builder
    {
        return $query->where('data', '<', now())
            ->orWhereIn('status', [self::STATUS_FINALIZADA, self::STATUS_CANCELADA])
            ->orderBy('data', 'desc');
    }

    /**
     * Métodos de verificação de estado
     */
    public function isAberta(): bool
    {
        return $this->status === self::STATUS_ABERTA;
    }

    public function isConfirmada(): bool
    {
        return $this->status === self::STATUS_CONFIRMADA;
    }

    public function temTimesDefinidos(): bool
    {
        return $this->status === self::STATUS_TIMES_DEFINIDOS;
    }

    public function isFinalizada(): bool
    {
        return $this->status === self::STATUS_FINALIZADA;
    }

    public function isCancelada(): bool
    {
        return $this->status === self::STATUS_CANCELADA;
    }

    /**
     * Métodos utilitários
     */
    public function getTotalConfirmados(): int
    {
        return $this->atletasConfirmados()->count();
    }

    public function temQuorum(): bool
    {
        $total = $this->getTotalConfirmados();
        return $total >= self::MIN_JOGADORES && $total <= self::MAX_JOGADORES;
    }

    public function podeDefinirTimes(): bool
    {
        $goleirosConfirmados = $this->atletasConfirmados()
            ->where('posicao', 'goleiro')
            ->count();

        return $this->temQuorum() &&
            $goleirosConfirmados >= 2 &&
            ($this->isAberta() || $this->isConfirmada());
    }

    public function getSomaHabilidadeTime(string $time): int
    {
        if ($time === self::TIME_PRETO) {
            return $this->timePreto()->sum('atletas.nivel_habilidade');
        }

        return $this->timeBranco()->sum('atletas.nivel_habilidade');
    }

    public function getBalanceamentoTimes(): array
    {
        return [
            'preto' => [
                'total_jogadores' => $this->timePreto()->count(),
                'soma_habilidade' => $this->getSomaHabilidadeTime(self::TIME_PRETO),
                'media_habilidade' => $this->timePreto()->count() > 0
                    ? round($this->getSomaHabilidadeTime(self::TIME_PRETO) / $this->timePreto()->count(), 2)
                    : 0,
            ],
            'branco' => [
                'total_jogadores' => $this->timeBranco()->count(),
                'soma_habilidade' => $this->getSomaHabilidadeTime(self::TIME_BRANCO),
                'media_habilidade' => $this->timeBranco()->count() > 0
                    ? round($this->getSomaHabilidadeTime(self::TIME_BRANCO) / $this->timeBranco()->count(), 2)
                    : 0,
            ],
        ];
    }
}

