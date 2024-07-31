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
        Schema::create('push_subscriptions', function (Blueprint $table) {
            $table->increments('id');
            $table->string('subscribable_type', 191);
            $table->unsignedBigInteger('subscribable_id');
            $table->string('endpoint', 500)->unique();
            $table->string('public_key', 191)->nullable();
            $table->string('auth_token', 191)->nullable();
            $table->string('content_encoding', 191)->nullable();
            $table->timestamps();

            $table->index(['subscribable_type', 'subscribable_id']);
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('push_subscriptions');
    }
};
