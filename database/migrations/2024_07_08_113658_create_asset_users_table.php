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
        Schema::create('asset_users', function (Blueprint $table) {
            $table->bigIncrements('id');
            $table->unsignedBigInteger('asset_id')->index('asset_users_asset_id_foreign');
            $table->unsignedBigInteger('user_id')->index('asset_users_user_id_foreign');
            $table->dateTime('assigned_date');
            $table->enum('status', ['allocated', 'ticket_raised', 'inactive'])->default('allocated');
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('asset_users');
    }
};
