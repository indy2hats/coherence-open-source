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
        Schema::table('asset_attribute_values', function (Blueprint $table) {
            $table->foreign(['attribute_value_id'])->references(['id'])->on('attribute_values')->onUpdate('restrict')->onDelete('cascade');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('asset_attribute_values', function (Blueprint $table) {
            $table->dropForeign('asset_attribute_values_attribute_value_id_foreign');
        });
    }
};
