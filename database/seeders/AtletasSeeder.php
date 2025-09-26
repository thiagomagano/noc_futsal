<?php

namespace Database\Seeders;

use App\Models\Atleta;
use Illuminate\Database\Seeder;

class AtletasSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {

        if (Atleta::count() === 0) {
            $atletas = [
                [
                    'nome' => 'Thiago Magano',
                    'apelido' => 'T.Magano',
                    'posicao' => 'linha',
                    'numero' => 10,
                    'nivel_habilidade' => 3,
                    'telefone' => '51993438767',
                    'status' => 'ativo',
                    'observacoes' => 'Um dos grandes..',
                ],
                [
                    'nome' => 'Paulo Corbacho',
                    'apelido' => 'P.Corbacho',
                    'posicao' => 'linha',
                    'numero' => 11,
                    'nivel_habilidade' => 4,
                    'telefone' => '51995660873',
                    'status' => 'ativo',
                    'observacoes' => 'Presidente Honorário',
                ],
                [
                    'nome' => 'Juliano Fraga',
                    'apelido' => 'Juliano F.',
                    'posicao' => 'linha',
                    'numero' => 13,
                    'nivel_habilidade' => 3,
                    'telefone' => '51993187169',
                    'status' => 'inativo',
                    'observacoes' => 'Ju nunca mais vai, joelho fudido.',
                ],
                [
                    'nome' => 'Marcel Magano',
                    'apelido' => 'Marcel',
                    'posicao' => 'linha',
                    'numero' => 19,
                    'nivel_habilidade' => 3,
                    'telefone' => '51981526250',
                    'status' => 'inativo',
                    'observacoes' => 'Pai do chico, so churras de vez em quando',
                ],
                [
                    'nome' => 'Rafael Vargas',
                    'apelido' => 'Vargas',
                    'posicao' => 'linha',
                    'numero' => 9,
                    'nivel_habilidade' => 4,
                    'telefone' => '51986377872',
                    'status' => 'ativo',
                    'observacoes' => '',
                ],
                [
                    'nome' => 'Jean',
                    'apelido' => 'Jean',
                    'posicao' => 'linha',
                    'numero' => 8,
                    'nivel_habilidade' => 4,
                    'telefone' => '5191517775',
                    'status' => 'ativo',
                    'observacoes' => '',
                ],
                [
                    'nome' => 'Claudio',
                    'apelido' => 'Claudio L.',
                    'posicao' => 'linha',
                    'numero' => 23,
                    'nivel_habilidade' => 3,
                    'telefone' => '51998643921',
                    'status' => 'ativo',
                    'observacoes' => 'Sempre fica no churras',
                ],
                [
                    'nome' => 'Luiz Diego',
                    'apelido' => 'L. Diego',
                    'posicao' => 'linha',
                    'numero' => 18,
                    'nivel_habilidade' => 3,
                    'telefone' => '51980358931',
                    'status' => 'ativo',
                    'observacoes' => 'Gaudério',
                ],
                [
                    'nome' => 'Higor',
                    'apelido' => 'Higor',
                    'posicao' => 'linha',
                    'numero' => 69,
                    'nivel_habilidade' => 3,
                    'telefone' => '51993949401',
                    'status' => 'ativo',
                    'observacoes' => '',
                ],
                [
                    'nome' => 'Paulinho Rick',
                    'apelido' => 'PR',
                    'posicao' => 'linha',
                    'numero' => 7,
                    'nivel_habilidade' => 4,
                    'telefone' => '51985002617',
                    'status' => 'inativo',
                    'observacoes' => 'Correria',
                ],
                [
                    'nome' => 'Bruno',
                    'apelido' => 'Brunão',
                    'posicao' => 'linha',
                    'numero' => 7,
                    'nivel_habilidade' => 2,
                    'telefone' => '5186204423',
                    'status' => 'ativo',
                    'observacoes' => '',
                ],
                [
                    'nome' => 'André Waldeka',
                    'apelido' => 'Waldeka',
                    'posicao' => 'linha',
                    'numero' => 5,
                    'nivel_habilidade' => 3,
                    'telefone' => '51995463999',
                    'status' => 'ativo',
                    'observacoes' => 'Tesoureiro',
                ],
                [
                    'nome' => 'Luan',
                    'apelido' => 'Luan',
                    'posicao' => 'linha',
                    'numero' => 6,
                    'nivel_habilidade' => 2,
                    'telefone' => '51985087342',
                    'status' => 'ativo',
                    'observacoes' => '',
                ],
                [
                    'nome' => 'Serginho',
                    'apelido' => 'Serginho',
                    'posicao' => 'linha',
                    'numero' => 13,
                    'nivel_habilidade' => 2,
                    'telefone' => '5195579543',
                    'status' => 'ativo',
                    'observacoes' => '',
                ],
                [
                    'nome' => 'Diogo',
                    'apelido' => 'Petrukio',
                    'posicao' => 'linha',
                    'numero' => 22,
                    'nivel_habilidade' => 5,
                    'telefone' => '51985839591',
                    'status' => 'ativo',
                    'observacoes' => 'Joga todo dia',
                ],
                [
                    'nome' => 'Thales Rocha',
                    'apelido' => 'Thales R.',
                    'posicao' => 'goleiro',
                    'numero' => 1,
                    'nivel_habilidade' => 5,
                    'telefone' => '51993790908',
                    'status' => 'inativo',
                    'observacoes' => '',
                ],
                [
                    'nome' => 'Rodolfo Matos',
                    'apelido' => 'Matos',
                    'posicao' => 'linha',
                    'numero' => 42,
                    'nivel_habilidade' => 4,
                    'telefone' => '51999752822',
                    'status' => 'ativo',
                    'observacoes' => '',
                ],
                [
                    'nome' => 'Willian',
                    'apelido' => 'Will',
                    'posicao' => 'linha',
                    'numero' => 77,
                    'nivel_habilidade' => 4,
                    'telefone' => '51983129875',
                    'status' => 'ativo',
                    'observacoes' => '',
                ],
                [
                    'nome' => 'Kleber',
                    'apelido' => 'Kiki',
                    'posicao' => 'goleiro',
                    'numero' => 12,
                    'nivel_habilidade' => 3,
                    'telefone' => '51981410140',
                    'status' => 'ativo',
                    'observacoes' => 'Churrasqueiro Goiano',
                ],
                [
                    'nome' => 'Carlos Garcias',
                    'apelido' => 'Carlos G.',
                    'posicao' => 'linha',
                    'numero' => 19,
                    'nivel_habilidade' => 3,
                    'telefone' => '51982442475',
                    'status' => 'ativo',
                    'observacoes' => 'Antigo toiço',
                ],
                [
                    'nome' => 'Saldanha',
                    'apelido' => 'Saldanha',
                    'posicao' => 'linha',
                    'numero' => 92,
                    'nivel_habilidade' => 4,
                    'telefone' => '51984600028',
                    'status' => 'ativo',
                    'observacoes' => '',
                ],

            ];

            foreach ($atletas as $atletaData) {
                Atleta::create($atletaData);
            }

            $this->command->info('Atletas criados com sucesso.');
        }
    }
}
