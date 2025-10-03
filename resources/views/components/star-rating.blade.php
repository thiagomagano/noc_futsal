@props([
    'value' => 0,
    'readonly' => false,
    'name' => 'rating',
    'size' => 'md',
])

@php
    $sizeClass = match ($size) {
        'sm' => 'text-sm',
        'lg' => 'text-xl',
        'xl' => 'text-2xl',
        default => 'text-base',
    };
@endphp

<div class="flex items-center gap-1" x-data="{
    rating: {{ $value }},
    hoverRating: 0,
    readonly: {{ $readonly ? 'true' : 'false' }}
}">

    @if (!$readonly)
        <input type="hidden" name="{{ $name }}" x-model="rating">
    @endif

    @for ($i = 1; $i <= 5; $i++)
        <button type="{{ $readonly ? 'button' : 'button' }}"
            class="focus:outline-none transition-colors duration-200 {{ $sizeClass }} {{ $readonly ? 'cursor-default' : 'cursor-pointer hover:scale-110' }}"
            @if (!$readonly) @click="rating = {{ $i }}"
                @mouseenter="hoverRating = {{ $i }}"
                @mouseleave="hoverRating = 0" @endif
            x-bind:class="{
                'text-yellow-400': (hoverRating >= {{ $i }}) || (hoverRating === 0 && rating >=
                    {{ $i }}),
                'text-gray-300': (hoverRating > 0 && hoverRating < {{ $i }}) || (hoverRating === 0 &&
                    rating < {{ $i }})
            }"
            {{ $readonly ? 'disabled' : '' }}>
            ★
        </button>
    @endfor

    @if (!$readonly)
        <span class="ml-2 text-sm text-gray-400"
            x-text="
            rating === 1 ? 'Iniciante' :
            rating === 2 ? 'Básico' :
            rating === 3 ? 'Intermediário' :
            rating === 4 ? 'Avançado' :
            rating === 5 ? 'Expert' : 'Selecione'
        "></span>
    @else
        <span class="ml-2 text-sm text-gray-400">
            {{ \App\Models\Atleta::NIVEIS_HABILIDADE[$value] ?? 'N/A' }}
        </span>
    @endif
</div>
