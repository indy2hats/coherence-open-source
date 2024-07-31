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
        Schema::table('asset_tickets', function (Blueprint $table) {
            $table->foreign(['asset_id'])->references(['id'])->on('assets')->onUpdate('restrict')->onDelete('restrict');
            $table->foreign(['asset_user_id'])->references(['id'])->on('asset_users')->onUpdate('restrict')->onDelete('restrict');
            $table->foreign(['user_id'])->references(['id'])->on('users')->onUpdate('restrict')->onDelete('cascade');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('asset_tickets', function (Blueprint $table) {
            $table->dropForeign('asset_tickets_asset_id_foreign');
            $table->dropForeign('asset_tickets_asset_user_id_foreign');
            $table->dropForeign('asset_tickets_user_id_foreign');
        });
    }
};
