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
        Schema::create('task_checklists', function (Blueprint $table) {
            $table->bigIncrements('id');
            $table->unsignedBigInteger('task_id')->index('task_checklists_task_id_foreign');
            $table->unsignedBigInteger('checklist_id')->index('task_checklists_checklist_id_foreign');
            $table->tinyInteger('developer_status')->default(0);
            $table->tinyInteger('reviewer_status')->default(0);
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('task_checklists');
    }
};
