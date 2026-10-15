<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('leads', function (Blueprint $table) {
            $table->id();
            $table->foreignId('property_id')->nullable()->constrained('properties')->nullOnDelete();
            $table->string('name');
            $table->string('phone');
            $table->string('email')->nullable();
            $table->enum('type', ['comprador', 'vendedor', 'interesado']);
            $table->enum('origin', ['facebook', 'instagram', 'whatsapp', 'marketplace', 'referido', 'otro']);
            $table->enum('status', ['nuevo', 'contactado', 'en_negociacion', 'cerrado', 'perdido'])->default('nuevo');
            $table->text('notes')->nullable();
            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('leads');
    }
};
