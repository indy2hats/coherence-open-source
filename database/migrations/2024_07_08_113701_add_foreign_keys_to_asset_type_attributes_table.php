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
        Schema::table('asset_type_attributes', function (Blueprint $table) {
            $table->foreign(['asset_type_id'])->references(['id'])->on('asset_types')->onUpdate('restrict')->onDelete('restrict');
            $table->foreign(['attribute_id'])->references(['id'])->on('attributes')->onUpdate('restrict')->onDelete('cascade');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('asset_type_attributes', function (Blueprint $table) {
            $table->dropForeign('asset_type_attributes_asset_type_id_foreign');
            $table->dropForeign('asset_type_attributes_attribute_id_foreign');
        });
    }
};
