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
        Schema::create('task_rejections', function (Blueprint $table) {
            $table->bigIncrements('id');
            $table->unsignedBigInteger('task_id')->index('task_rejections_task_id_foreign');
            $table->unsignedBigInteger('user_id')->index('task_rejections_user_id_foreign');
            $table->string('severity', 191);
            $table->longText('reason');
            $table->longText('comments')->nullable();
            $table->integer('score')->nullable();
            $table->unsignedBigInteger('rejected_by')->nullable()->index('task_rejections_rejected_by_foreign');
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('task_rejections');
    }
};
