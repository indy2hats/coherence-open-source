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
        Schema::create('title_description_catalogs', function (Blueprint $table) {
            $table->bigIncrements('id');
            $table->text('title');
            $table->longText('content');
            $table->text('value')->nullable();
            $table->text('file_upload')->nullable();
            $table->enum('type', ['CORE_VALUE', 'CORE_VALUE_SETTINGS']);
            $table->enum('status', ['ACTIVE', 'INACTIVE'])->default('ACTIVE');
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('title_description_catalogs');
    }
};
