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
        Schema::create('attendances', function (Blueprint $table) {
            $table->id();
            // Llave foránea que vincula la asistencia con el evento
            $table->foreignId('event_id')->constrained()->onDelete('cascade');

            $table->string('name');
            $table->string('email');
            $table->string('document_id')->nullable(); // Cédula, DNI o Pasaporte

            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('attendances');
    }
};
