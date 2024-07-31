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
        Schema::create('project_credentials', function (Blueprint $table) {
            $table->bigIncrements('id');
            $table->string('type', 191);
            $table->string('username', 191)->nullable();
            $table->text('password')->nullable();
            $table->longText('value');
            $table->unsignedBigInteger('project_id')->index('project_credentials_project_id_foreign');
            $table->longText('path')->nullable();
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('project_credentials');
    }
};
