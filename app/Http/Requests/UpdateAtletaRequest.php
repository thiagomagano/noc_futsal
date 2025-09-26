<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Validation\Rule;

class UpdateAtletaRequest extends FormRequest
{
    public function authorize(): bool
    {
        return true;
    }

    public function rules(): array
    {
        $atletaId = $this->route('atleta')?->id;

        return [
            'nome' => [
                'required',
                'string',
                'min:2',
                'max:100',
                'regex:/^[\p{L}\s\-\'\.]+$/u'
            ],
            'apelido' => [
                'nullable',
                'string',
                'min:2',
                'max:50',
                'regex:/^[\p{L}\s\-\'\.]+$/u'
            ],
            'posicao' => [
                'required',
                Rule::in(['goleiro', 'linha'])
            ],
            'nivel_habilidade' => [
                'required',
                'integer',
                'min:1',
                'max:5'
            ],
            'telefone' => [
                'required',
                'string',
                'regex:/^(\(?\d{2}\)?\s?)?\d{4,5}-?\d{4}$/',
                Rule::unique('atletas', 'telefone')->ignore($atletaId)
            ],
            'status' => [
                'required',
                Rule::in(['ativo', 'inativo'])
            ],
            'observacoes' => [
                'nullable',
                'string',
                'max:1000'
            ]
        ];
    }

    public function messages(): array
    {
        return (new StoreAtletaRequest())->messages();
    }

    protected function prepareForValidation(): void
    {
        // Mesma lógica do StoreAtletaRequest
        if ($this->telefone) {
            $telefone = preg_replace('/[^0-9]/', '', $this->telefone);
            $this->merge(['telefone' => $telefone]);
        }

        $this->merge([
            'nome' => trim($this->nome ?? ''),
            'apelido' => trim($this->apelido ?? '') ?: null,
            'observacoes' => trim($this->observacoes ?? '') ?: null,
        ]);
    }
}
