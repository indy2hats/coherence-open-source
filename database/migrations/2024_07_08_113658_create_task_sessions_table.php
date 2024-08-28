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
        Schema::create('task_sessions', function (Blueprint $table) {
            $table->bigIncrements('id');
            $table->unsignedBigInteger('task_id')->index('task_sessions_task_id_foreign');
            $table->unsignedBigInteger('user_id')->index('task_sessions_user_id_foreign');
            $table->string('current_status', 191);
            $table->dateTime('start_time')->nullable();
            $table->dateTime('end_time')->nullable();
            $table->unsignedBigInteger('total')->nullable();
            $table->unsignedBigInteger('billed_today')->nullable();
            $table->longText('comments')->nullable();
            $table->string('session_type', 191)->nullable();
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('task_sessions');
    }
};
