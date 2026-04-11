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
            $table->string('title');
            $table->string('author');
            $table->string('journal_name')->nullable();
            $table->string('volume_and_year')->nullable();
            $table->string('isbn', 50)->nullable();
            $table->string('legal_deposit_number', 100)->nullable();
            $table->string('type_of_publication', 100);
            $table->string('research_area', 150);
            $table->text('abstract');
            $table->text('keywords')->nullable(); // text permite guardar una cadena larga o un JSON
            $table->string('pdf_path'); // Aquí guardaremos la ruta relativa del archivo
            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('publications');
    }
};
