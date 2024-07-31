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
        Schema::create('task_assigned_users_hours', function (Blueprint $table) {
            $table->bigIncrements('id');
            $table->unsignedBigInteger('task_id')->index('task_assigned_users_hours_task_id_foreign');
            $table->unsignedBigInteger('user_id')->index('task_assigned_users_hours_user_id_foreign');
            $table->date('date')->nullable();
            $table->string('hour', 191)->nullable();
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('task_assigned_users_hours');
    }
};
