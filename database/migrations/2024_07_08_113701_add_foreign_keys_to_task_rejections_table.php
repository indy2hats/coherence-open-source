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
        Schema::table('task_rejections', function (Blueprint $table) {
            $table->foreign(['rejected_by'])->references(['id'])->on('users')->onUpdate('restrict')->onDelete('cascade');
            $table->foreign(['task_id'])->references(['id'])->on('tasks')->onUpdate('restrict')->onDelete('cascade');
            $table->foreign(['user_id'])->references(['id'])->on('users')->onUpdate('restrict')->onDelete('cascade');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('task_rejections', function (Blueprint $table) {
            $table->dropForeign('task_rejections_rejected_by_foreign');
            $table->dropForeign('task_rejections_task_id_foreign');
            $table->dropForeign('task_rejections_user_id_foreign');
        });
    }
};
