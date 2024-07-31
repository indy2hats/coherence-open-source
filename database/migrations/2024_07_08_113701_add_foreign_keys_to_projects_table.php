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
        Schema::table('projects', function (Blueprint $table) {
            $table->foreign(['client_id'])->references(['id'])->on('clients')->onUpdate('restrict')->onDelete('restrict');
            $table->foreign(['technology_id'])->references(['id'])->on('technologies')->onUpdate('restrict')->onDelete('restrict');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('projects', function (Blueprint $table) {
            $table->dropForeign('projects_client_id_foreign');
            $table->dropForeign('projects_technology_id_foreign');
        });
    }
};
