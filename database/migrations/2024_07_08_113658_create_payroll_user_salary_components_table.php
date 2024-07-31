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
        Schema::create('payroll_user_salary_components', function (Blueprint $table) {
            $table->bigIncrements('id');
            $table->unsignedBigInteger('payroll_user_id')->index('payroll_user_salary_components_payroll_user_id_foreign');
            $table->unsignedBigInteger('salary_component_id')->index('payroll_user_salary_components_salary_component_id_foreign');
            $table->decimal('amount', 15);
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('payroll_user_salary_components');
    }
};
