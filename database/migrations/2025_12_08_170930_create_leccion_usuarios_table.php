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
        Schema::create('lessons_users', function (Blueprint $table) {
            $table->id();
            $table->unsignedBigInteger('user_id'); // ID del usuario
            $table->unsignedBigInteger('leccion_id'); // ID del curso
            $table->timestamps();

            // Relaciones (si las necesitas)
            $table->foreign('user_id')->references('id')->on('users')->onDelete('cascade');
            $table->foreign('leccion_id')->references('id')->on('lessons')->onDelete('cascade');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('lessons_users');
    }
};
