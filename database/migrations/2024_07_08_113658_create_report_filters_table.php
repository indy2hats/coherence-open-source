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
        Schema::create('report_filters', function (Blueprint $table) {
            $table->bigIncrements('id');
            $table->unsignedBigInteger('user_id')->index('report_filters_user_id_foreign');
            $table->string('name', 191);
            $table->string('slug', 191);
            $table->string('report_name', 191);
            $table->json('project_ids')->nullable();
            $table->json('client_ids')->nullable();
            $table->json('session_type_ids')->nullable();
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('report_filters');
    }
};
