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
        Schema::table('asset_documents', function (Blueprint $table) {
            $table->foreign(['asset_id'])->references(['id'])->on('assets')->onUpdate('restrict')->onDelete('cascade');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('asset_documents', function (Blueprint $table) {
            $table->dropForeign('asset_documents_asset_id_foreign');
        });
    }
};
