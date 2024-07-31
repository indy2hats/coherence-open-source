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
        Schema::table('taxonomy_lists', function (Blueprint $table) {
            $table->foreign(['taxonomy_id'])->references(['id'])->on('taxonomies')->onUpdate('restrict')->onDelete('cascade');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('taxonomy_lists', function (Blueprint $table) {
            $table->dropForeign('taxonomy_lists_taxonomy_id_foreign');
        });
    }
};
