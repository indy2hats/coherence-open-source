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
        Schema::table('task_checklists', function (Blueprint $table) {
            $table->foreign(['checklist_id'])->references(['id'])->on('checklists')->onUpdate('restrict')->onDelete('cascade');
            $table->foreign(['task_id'])->references(['id'])->on('tasks')->onUpdate('restrict')->onDelete('cascade');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('task_checklists', function (Blueprint $table) {
            $table->dropForeign('task_checklists_checklist_id_foreign');
            $table->dropForeign('task_checklists_task_id_foreign');
        });
    }
};
