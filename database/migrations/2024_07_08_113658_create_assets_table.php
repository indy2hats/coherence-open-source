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
        Schema::create('assets', function (Blueprint $table) {
            $table->bigIncrements('id');
            $table->unsignedBigInteger('asset_type_id')->nullable();
            $table->unsignedBigInteger('vendor_id')->nullable();
            $table->string('name', 191);
            $table->string('configuration', 191)->nullable();
            $table->string('serial_number', 191);
            $table->dateTime('purchased_date')->nullable();
            $table->string('value', 191);
            $table->string('warranty', 191)->nullable();
            $table->enum('status', ['allocated', 'non_allocated', 'ticket_raised', 'inactive'])->default('non_allocated');
            $table->softDeletes();
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('assets');
    }
};
