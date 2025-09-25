<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Validation\Rule;

class StoreAtletaRequest extends FormRequest
{
    public function authorize(): bool
    {
        return true; // Middleware auth já controla
    }

    public function rules(): array
    {
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
                'unique:atletas,telefone'
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
        return [
            'nome.required' => 'O nome é obrigatório.',
            'nome.min' => 'O nome deve ter pelo menos 2 caracteres.',
            'nome.max' => 'O nome não pode ter mais de 100 caracteres.',
            'nome.regex' => 'O nome contém caracteres inválidos.',

            'apelido.min' => 'O apelido deve ter pelo menos 2 caracteres.',
            'apelido.max' => 'O apelido não pode ter mais de 50 caracteres.',
            'apelido.regex' => 'O apelido contém caracteres inválidos.',

            'posicao.required' => 'A posição é obrigatória.',
            'posicao.in' => 'Posição inválida selecionada.',

            'nivel_habilidade.required' => 'O nível de habilidade é obrigatório.',
            'nivel_habilidade.integer' => 'O nível deve ser um número inteiro.',
            'nivel_habilidade.min' => 'O nível mínimo é 1.',
            'nivel_habilidade.max' => 'O nível máximo é 5.',

            'telefone.required' => 'O telefone é obrigatório.',
            'telefone.regex' => 'Formato de telefone inválido. Use (XX) XXXXX-XXXX ou similar.',
            'telefone.unique' => 'Este telefone já está cadastrado.',

            'status.required' => 'O status é obrigatório.',
            'status.in' => 'Status inválido selecionado.',

            'observacoes.max' => 'As observações não podem ter mais de 1000 caracteres.'
        ];
    }

    protected function prepareForValidation(): void
    {
        // Limpar e formatar telefone
        if ($this->telefone) {
            $telefone = preg_replace('/[^0-9]/', '', $this->telefone);
            $this->merge(['telefone' => $telefone]);
        }

        // Trim nos campos de texto
        $this->merge([
            'nome' => trim($this->nome ?? ''),
            'apelido' => trim($this->apelido ?? '') ?: null,
            'observacoes' => trim($this->observacoes ?? '') ?: null,
        ]);
    }
}