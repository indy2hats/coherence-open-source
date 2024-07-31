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
        Schema::create('user_bank_details', function (Blueprint $table) {
            $table->increments('id');
            $table->unsignedBigInteger('user_id')->index('user_bank_details_user_id_foreign');
            $table->string('bank_name', 200)->nullable();
            $table->string('branch', 200)->nullable();
            $table->string('account_no', 25)->nullable();
            $table->string('ifsc', 25)->nullable();
            $table->string('pan', 20)->nullable();
            $table->string('uan', 20)->nullable();
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('user_bank_details');
    }
};
