<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('articles', function (Blueprint $table) {
            $table->id();

            // 🔥 Cambiamos a jsonb para guardar los idiomas
            $table->jsonb('title');
            $table->jsonb('description');

            $table->string('link'); // URL del artículo o video
            $table->date('date');

            // Limitamos las categorías permitidas a nivel de base de datos
            $table->enum('category', ['institutional', 'conference', 'interview', 'news']);

            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('articles');
    }
};
