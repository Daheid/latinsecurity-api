<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('publications', function (Blueprint $table) {
            $table->id();

            // 🔥 Campos traducibles convertidos a jsonb
            $table->jsonb('title');
            $table->jsonb('type_of_publication');
            $table->jsonb('research_area');
            $table->jsonb('abstract');
            $table->jsonb('keywords')->nullable();

            // Campos estáticos (No se traducen)
            $table->string('author');
            $table->string('journal_name')->nullable();
            $table->string('volume_and_year')->nullable();
            $table->string('isbn', 50)->nullable();
            $table->string('legal_deposit_number', 100)->nullable();
            $table->string('pdf_path');

            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('publications');
    }
};
