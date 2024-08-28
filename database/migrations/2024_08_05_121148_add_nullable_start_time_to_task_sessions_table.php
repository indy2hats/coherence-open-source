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
        Schema::table('task_sessions', function (Blueprint $table) {
            $table->dateTime('start_time')->nullable()->change();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('task_sessions', function (Blueprint $table) {
            $table->dateTime('start_time')->nullable(false)->change();
        });
    }
};
