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
        Schema::create('tasks', function (Blueprint $table) {
            $table->bigIncrements('id');
            $table->unsignedBigInteger('project_id')->index('tasks_project_id_foreign');
            $table->string('code', 191);
            $table->string('title', 191);
            $table->string('priority', 191)->nullable();
            $table->string('estimated_time', 191)->nullable();
            $table->string('actual_estimated_time', 191)->nullable();
            $table->string('time_spent', 191)->nullable();
            $table->longText('description')->nullable();
            $table->bigInteger('percent_complete')->nullable()->default(0);
            $table->string('task_url', 191)->nullable();
            $table->unsignedBigInteger('created_by');
            $table->string('task_id', 191)->nullable();
            $table->dateTime('start_date')->nullable();
            $table->dateTime('end_date')->nullable();
            $table->string('status', 191)->nullable();
            $table->integer('parent_id')->nullable();
            $table->unsignedBigInteger('reviewer_id')->nullable()->index('tasks_reviewer_id_foreign');
            $table->string('tag', 191)->nullable();
            $table->longText('notes')->nullable();
            $table->timestamps();
            $table->integer('order_no')->nullable();
            $table->integer('add_to_board');
            $table->integer('sub_task_order')->nullable();
            $table->string('is_archived', 191)->nullable()->default('1');
            $table->softDeletes();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('tasks');
    }
};
