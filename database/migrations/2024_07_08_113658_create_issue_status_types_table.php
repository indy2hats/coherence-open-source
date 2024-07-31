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
        Schema::create('issue_status_types', function (Blueprint $table) {
            $table->bigIncrements('id');
            $table->string('name', 191);
            $table->enum('is_default', ['yes', 'no'])->default('no');
            $table->enum('is_tracking', ['yes', 'no'])->default('no');
            $table->softDeletes();
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('issue_status_types');
    }
};
