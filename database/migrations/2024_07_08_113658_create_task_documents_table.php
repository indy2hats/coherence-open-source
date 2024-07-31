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
        Schema::create('task_documents', function (Blueprint $table) {
            $table->bigIncrements('id');
            $table->longText('path');
            $table->unsignedBigInteger('task_id')->index('task_documents_task_id_foreign');
            $table->unsignedBigInteger('comment_id')->nullable()->index('task_documents_comment_id_foreign');
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('task_documents');
    }
};
