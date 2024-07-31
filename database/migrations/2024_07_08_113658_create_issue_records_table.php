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
        Schema::create('issue_records', function (Blueprint $table) {
            $table->bigIncrements('id');
            $table->unsignedBigInteger('project_id')->index('issue_records_project_id_foreign');
            $table->unsignedBigInteger('added_by')->index('issue_records_added_by_foreign');
            $table->string('category', 191)->nullable();
            $table->string('title', 191);
            $table->longText('description');
            $table->longText('solution');
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('issue_records');
    }
};
