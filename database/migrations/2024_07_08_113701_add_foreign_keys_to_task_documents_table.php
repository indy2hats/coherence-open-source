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
        Schema::table('task_documents', function (Blueprint $table) {
            $table->foreign(['comment_id'])->references(['id'])->on('comments')->onUpdate('restrict')->onDelete('cascade');
            $table->foreign(['task_id'])->references(['id'])->on('tasks')->onUpdate('restrict')->onDelete('cascade');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('task_documents', function (Blueprint $table) {
            $table->dropForeign('task_documents_comment_id_foreign');
            $table->dropForeign('task_documents_task_id_foreign');
        });
    }
};
