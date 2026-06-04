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

            // 🔥 Campos traducibles convertidos a jsonb
            $table->jsonb('title');
            $table->jsonb('subtitle');
            $table->jsonb('location');
            $table->date('date');
            $table->time('start_time');
            $table->time('end_time');
            $table->jsonb('topics');

            // 🔥 Campos opcionales convertidos a jsonb
            $table->jsonb('objectives')->nullable();
            $table->jsonb('scopes')->nullable();

            $table->enum('status', ['active', 'ready'])->default('active');

            // 🔥 Resultados también a jsonb
            $table->jsonb('result')->nullable();
            $table->jsonb('implementation')->nullable();

            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('events');
    }
};
