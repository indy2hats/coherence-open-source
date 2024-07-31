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
        Schema::create('compensatories', function (Blueprint $table) {
            $table->bigIncrements('id');
            $table->unsignedBigInteger('user_id')->index('compensatories_user_id_foreign');
            $table->date('date');
            $table->enum('session', ['Half Day', 'Full Day', 'First Half', 'Second Half']);
            $table->string('status', 191);
            $table->string('reason', 191);
            $table->unsignedBigInteger('approved_by')->nullable()->index('compensatories_approved_by_foreign');
            $table->string('reason_for_rejection', 191)->nullable();
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('compensatories');
    }
};
