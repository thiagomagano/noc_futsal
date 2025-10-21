<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration {
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        Schema::create('atletas', function (Blueprint $table) {
            $table->id();
            $table->string('nome');
            $table->string('apelido')->nullable();
            $table->tinyInteger('numero')->nullable();
            $table->enum('posicao', ['linha', 'goleiro'])->default('linha');
            $table->tinyInteger('nivel_habilidade')->default(3)->comment('Nivel de 1 a 5');
            $table->string('telefone')->nullable();
            $table->enum('status', ['ativo', 'inativo'])->default('ativo');
            $table->text('observacoes')->nullable();
            $table->timestamps();
            $table->softDeletes();

            $table->index(['status', 'created_at']);
            $table->index('posicao');
            $table->index('nivel_habilidade');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('atletas');
    }
};
