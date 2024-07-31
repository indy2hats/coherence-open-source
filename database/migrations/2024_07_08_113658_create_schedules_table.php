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
        Schema::create('schedules', function (Blueprint $table) {
            $table->bigIncrements('id');
            $table->unsignedBigInteger('recruitment_id')->index('schedules_recruitment_id_foreign');
            $table->dateTime('machine_test1')->nullable();
            $table->dateTime('machine_test2')->nullable();
            $table->dateTime('technical_interview')->nullable();
            $table->dateTime('hr_interview')->nullable();
            $table->string('machine_test1_status', 191);
            $table->string('machine_test2_status', 191)->nullable();
            $table->string('technical_interview_status', 191);
            $table->string('hr_interview_status', 191);
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('schedules');
    }
};
