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
        Schema::create('asset_tickets', function (Blueprint $table) {
            $table->bigIncrements('id');
            $table->string('type', 191)->nullable();
            $table->unsignedBigInteger('asset_user_id')->index('asset_tickets_asset_user_id_foreign');
            $table->unsignedBigInteger('asset_id')->index('asset_tickets_asset_id_foreign');
            $table->unsignedBigInteger('user_id')->index('asset_tickets_user_id_foreign');
            $table->unsignedBigInteger('status_id')->nullable();
            $table->enum('status', ['open', 'closed'])->default('open');
            $table->text('issue');
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('asset_tickets');
    }
};
