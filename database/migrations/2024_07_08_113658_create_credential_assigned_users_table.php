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
        Schema::create('credential_assigned_users', function (Blueprint $table) {
            $table->bigIncrements('id');
            $table->unsignedBigInteger('credential_id')->index('credential_assigned_users_credential_id_foreign');
            $table->unsignedBigInteger('user_id')->index('credential_assigned_users_user_id_foreign');
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('credential_assigned_users');
    }
};
