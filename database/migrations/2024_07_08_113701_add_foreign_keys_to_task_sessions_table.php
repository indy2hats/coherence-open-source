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
        Schema::table('task_sessions', function (Blueprint $table) {
            $table->foreign(['task_id'])->references(['id'])->on('tasks')->onUpdate('restrict')->onDelete('cascade');
            $table->foreign(['user_id'])->references(['id'])->on('users')->onUpdate('restrict')->onDelete('cascade');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('task_sessions', function (Blueprint $table) {
            $table->dropForeign('task_sessions_task_id_foreign');
            $table->dropForeign('task_sessions_user_id_foreign');
        });
    }
};
