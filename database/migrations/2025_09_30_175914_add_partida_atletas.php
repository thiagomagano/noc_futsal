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
        Schema::create('partida_atletas', function (Blueprint $table) {
            $table->id();
            $table->foreignId('partida_id')
                ->constrained('partidas')
                ->onDelete('cascade');
            $table->foreignId('atleta_id')
                ->constrained('atletas')
                ->onDelete('cascade');
            $table->boolean('confirmado')->default(false);
            $table->enum('time', ['preto', 'branco'])->nullable();
            $table->timestamps();


            $table->unique(['partida_id', 'atleta_id']);


            $table->index('confirmado');
            $table->index('time');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('partida_atletas');
    }
};