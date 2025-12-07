<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        Schema::create('comentarios', function (Blueprint $table) {
            $table->id();
            $table->string("user")->nullable();
            $table->string("ip")->nullable();
            $table->string("message")->nullable();
             $table->foreignId('lesson_id')
                ->constrained()
                ->onDelete('cascade'); // Opcional: elimina las secciones si se elimina el curso
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('comentarios');
    }
};
