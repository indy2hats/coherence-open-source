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
        Schema::table('user_daily_checklists', function (Blueprint $table) {
            $table->foreign(['checklist_id'])->references(['id'])->on('daily_checklists')->onUpdate('restrict')->onDelete('cascade');
            $table->foreign(['user_id'])->references(['id'])->on('users')->onUpdate('restrict')->onDelete('cascade');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('user_daily_checklists', function (Blueprint $table) {
            $table->dropForeign('user_daily_checklists_checklist_id_foreign');
            $table->dropForeign('user_daily_checklists_user_id_foreign');
        });
    }
};
