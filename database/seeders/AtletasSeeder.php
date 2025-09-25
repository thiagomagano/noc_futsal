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
                    'nome' => 'João Silva',
                    'apelido' => 'Joãozinho',
                    'posicao' => 'goleiro',
                    'nivel_habilidade' => 4,
                    'telefone' => '11999998888',
                    'status' => 'ativo',
                    'observacoes' => 'Goleiro experiente, bom com os pés',
                ],
                [
                    'nome' => 'Pedro Santos',
                    'apelido' => 'Pedrinho',
                    'posicao' => 'linha',
                    'nivel_habilidade' => 5,
                    'telefone' => '11999997777',
                    'status' => 'ativo',
                    'observacoes' => 'Capitão do time, excelente marcação',
                ],
                [
                    'nome' => 'Carlos Oliveira',
                    'apelido' => 'Carlinhos',
                    'posicao' => 'linha',
                    'nivel_habilidade' => 3,
                    'telefone' => '11999996666',
                    'status' => 'ativo',
                    'observacoes' => 'Rápido nas laterais, bom cruzamento',
                ],
                [
                    'nome' => 'Roberto Costa',
                    'apelido' => 'Beto',
                    'posicao' => 'linha',
                    'nivel_habilidade' => 4,
                    'telefone' => '11999995555',
                    'status' => 'ativo',
                    'observacoes' => 'Finalizador nato, forte fisicamente',
                ],
                [
                    'nome' => 'Lucas Ferreira',
                    'apelido' => 'Lucão',
                    'posicao' => 'linha',
                    'nivel_habilidade' => 2,
                    'telefone' => '11999994444',
                    'status' => 'ativo',
                    'observacoes' => 'Jovem promessa, precisa desenvolver mais a técnica',
                ],
                [
                    'nome' => 'Marcelo Souza',
                    'apelido' => 'Marcelinho',
                    'posicao' => 'linha',
                    'nivel_habilidade' => 3,
                    'telefone' => '11999993333',
                    'status' => 'inativo',
                    'observacoes' => 'Lesionado, retorna em breve',
                ],
                [
                    'nome' => 'Anderson Lima',
                    'apelido' => null,
                    'posicao' => 'goleiro',
                    'nivel_habilidade' => 3,
                    'telefone' => '11999992222',
                    'status' => 'ativo',
                    'observacoes' => 'Goleiro reserva, ainda em adaptação',
                ],
                [
                    'nome' => 'Felipe Alves',
                    'apelido' => 'Felipão',
                    'posicao' => 'linha',
                    'nivel_habilidade' => 5,
                    'telefone' => '11999991111',
                    'status' => 'ativo',
                    'observacoes' => 'Artilheiro do time, excelente técnica individual',
                ],
                [
                    'nome' => 'Ricardo Mendes',
                    'apelido' => 'Ricardinho',
                    'posicao' => 'linha',
                    'nivel_habilidade' => 4,
                    'telefone' => '11999990000',
                    'status' => 'ativo',
                    'observacoes' => 'Versátil, joga em qualquer posição de linha',
                ],
                [
                    'nome' => 'Thiago Rodrigues',
                    'apelido' => 'Thiaguinho',
                    'posicao' => 'linha',
                    'nivel_habilidade' => 2,
                    'telefone' => '11999989999',
                    'status' => 'ativo',
                    'observacoes' => 'Iniciante, mas muito dedicado nos treinos',
                ],
            ];

            foreach ($atletas as $atletaData) {
                Atleta::create($atletaData);
            }

            $this->command->info('Atletas criados com sucesso.');
        }
    }
}
