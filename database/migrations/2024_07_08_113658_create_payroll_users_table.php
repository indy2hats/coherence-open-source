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
        Schema::create('payroll_users', function (Blueprint $table) {
            $table->bigIncrements('id');
            $table->unsignedBigInteger('payroll_id')->index('payroll_users_payroll_id_foreign');
            $table->unsignedBigInteger('user_id')->index('payroll_users_user_id_foreign');
            $table->decimal('gross_salary', 15);
            $table->decimal('incentives', 15)->nullable();
            $table->decimal('total_earnings', 15);
            $table->decimal('total_deductions', 15);
            $table->decimal('net_salary', 15);
            $table->decimal('monthly_ctc', 15);
            $table->enum('status', ['pending', 'approved']);
            $table->decimal('no_of_leaves', 2, 1)->nullable();
            $table->decimal('loss_of_pay', 15)->nullable();
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('payroll_users');
    }
};
