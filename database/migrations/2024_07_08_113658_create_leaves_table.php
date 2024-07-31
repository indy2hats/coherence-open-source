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
        Schema::create('leaves', function (Blueprint $table) {
            $table->bigIncrements('id');
            $table->unsignedBigInteger('user_id')->index('leaves_user_id_foreign');
            $table->date('from_date');
            $table->date('to_date');
            $table->string('type', 191);
            $table->string('status', 191);
            $table->unsignedBigInteger('approved_by')->nullable()->index('leaves_approved_by_foreign');
            $table->enum('session', ['First Half', 'Second Half', 'Full Day']);
            $table->enum('lop', ['Yes', 'No'])->default('No');
            $table->string('reason', 191);
            $table->string('reason_for_rejection', 191)->nullable();
            $table->string('email_code', 191)->nullable();
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('leaves');
    }
};
