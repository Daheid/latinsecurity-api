<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;
use Illuminate\Support\Facades\DB;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        // First drop the existing check constraint
        DB::statement('ALTER TABLE articles DROP CONSTRAINT IF EXISTS articles_category_check');
        
        // Add the new check constraint containing linkedin
        DB::statement("ALTER TABLE articles ADD CONSTRAINT articles_category_check CHECK (category::text = ANY (ARRAY['institutional'::character varying, 'conference'::character varying, 'interview'::character varying, 'news'::character varying, 'linkedin'::character varying]::text[]))");
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        // Revert check constraint to original
        DB::statement('ALTER TABLE articles DROP CONSTRAINT IF EXISTS articles_category_check');
        DB::statement("ALTER TABLE articles ADD CONSTRAINT articles_category_check CHECK (category::text = ANY (ARRAY['institutional'::character varying, 'conference'::character varying, 'interview'::character varying, 'news'::character varying]::text[]))");
    }
};
