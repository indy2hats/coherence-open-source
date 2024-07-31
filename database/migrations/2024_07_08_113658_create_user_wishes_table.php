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
        Schema::create('user_wishes', function (Blueprint $table) {
            $table->bigIncrements('id');
            $table->date('date');
            $table->integer('user_id')->nullable();
            $table->string('type', 191);
            $table->string('title', 191);
            $table->longText('image');
            $table->string('file_type', 191);
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('user_wishes');
    }
};
