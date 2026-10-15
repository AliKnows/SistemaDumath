<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('JugadorVisitante', function (Blueprint $table) {
            $table->id();
            $table->string('nombre');
            $table->unsignedTinyInteger('edad');
            $table->string('rol', 30);
            $table->string('contacto', 30);
            $table->text('opinion');
            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('JugadorVisitante');
    }
};
