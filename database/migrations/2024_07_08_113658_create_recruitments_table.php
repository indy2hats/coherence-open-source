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
        Schema::create('recruitments', function (Blueprint $table) {
            $table->bigIncrements('id');
            $table->string('name', 191);
            $table->string('email', 191)->unique();
            $table->string('phone', 191);
            $table->string('category', 191);
            $table->string('resume', 191);
            $table->string('status', 191);
            $table->text('description')->nullable();
            $table->string('source')->nullable();
            $table->date('career_start_date')->nullable();
            $table->dateTime('applied_date')->nullable();
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('recruitments');
    }
};
