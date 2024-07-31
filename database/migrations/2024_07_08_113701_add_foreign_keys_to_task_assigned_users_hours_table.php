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
        Schema::table('task_assigned_users_hours', function (Blueprint $table) {
            $table->foreign(['task_id'])->references(['id'])->on('tasks')->onUpdate('restrict')->onDelete('cascade');
            $table->foreign(['user_id'])->references(['id'])->on('users')->onUpdate('restrict')->onDelete('cascade');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('task_assigned_users_hours', function (Blueprint $table) {
            $table->dropForeign('task_assigned_users_hours_task_id_foreign');
            $table->dropForeign('task_assigned_users_hours_user_id_foreign');
        });
    }
};
