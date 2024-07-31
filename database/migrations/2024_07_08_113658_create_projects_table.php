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
        Schema::create('projects', function (Blueprint $table) {
            $table->bigIncrements('id');
            $table->unsignedBigInteger('client_id')->index('projects_client_id_foreign');
            $table->string('project_name', 191);
            $table->string('project_id', 191)->unique();
            $table->string('project_type', 191)->nullable();
            $table->dateTime('start_date')->nullable();
            $table->dateTime('end_date')->nullable();
            $table->string('priority', 191)->nullable();
            $table->string('cost_type', 191)->nullable();
            $table->string('rate', 191)->nullable();
            $table->longText('description')->nullable();
            $table->unsignedBigInteger('technology_id')->nullable()->index('projects_technology_id_foreign');
            $table->timestamps();
            $table->string('site_url', 191)->nullable();
            $table->string('estimated_hours', 191)->nullable();
            $table->string('status', 191)->nullable();
            $table->string('category', 191)->nullable();
            $table->string('is_archived', 191)->nullable()->default('0');
            $table->softDeletes();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('projects');
    }
};
