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
       Schema::create('pagos', function (Blueprint $table) {
            $table->id();
            $table->unsignedBigInteger('user_id'); // ID del usuario
            $table->unsignedBigInteger('course_id'); // ID del curso
            $table->integer('price')->nullable(); // Valor del pago
            $table->json('detalle')->nullable(); // Campo JSON para datos extra
            $table->string('status')->nullable(); // Campo JSON para datos extra
            $table->timestamps();

            // Relaciones (si las necesitas)
            $table->foreign('user_id')->references('id')->on('users')->onDelete('cascade');
            $table->foreign('course_id')->references('id')->on('courses')->onDelete('cascade');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('pagos');
    }
};
