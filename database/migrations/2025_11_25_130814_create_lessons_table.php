<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    /*
    lessons: [
      { id: 1, title: "1.1 Introducción y Configuración", duration: "5m", type: "video" },
      { id: 2, title: "1.2 Tipos de Datos Primitivos", duration: "10m", type: "video" },
      { id: 3, title: "1.3 Variables (let vs const)", duration: "8m", type: "markdown" },
      { id: 4, title: "1.4 Operadores y Lógica Booleana", duration: "12m", type: "video" },
    ]
    */
    public function up(): void
    {
        Schema::create('lessons', function (Blueprint $table) {
            $table->id();
            $table->string('title');
            $table->string('duration');
            $table->string('type')->default('video');
            $table->boolean('isFree')->default(false);
            $table->string('url_video')->nullable();
            $table->longText('content')->nullable();
            $table->text('description')->nullable();
            $table->string('url_download')->nullable();
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('lessons');
    }
};
