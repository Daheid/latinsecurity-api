<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('events', function (Blueprint $table) {
            $table->id();
            // Campos obligatorios para la creación
            $table->string('title');
            $table->string('subtitle');
            $table->string('location');
            $table->date('date');
            $table->time('start_time');
            $table->time('end_time');
            $table->text('topics');

            // Campos opcionales para la creación
            $table->text('objectives')->nullable();
            $table->text('scopes')->nullable(); // Alcances

            // Campos para el estado y cierre del evento
            $table->enum('status', ['active', 'ready'])->default('active');
            $table->text('result')->nullable();
            $table->text('implementation')->nullable();

            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('events');
    }
};
