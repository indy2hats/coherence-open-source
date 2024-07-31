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
        Schema::table('payroll_user_salary_components', function (Blueprint $table) {
            $table->foreign(['payroll_user_id'])->references(['id'])->on('payroll_users')->onUpdate('restrict')->onDelete('cascade');
            $table->foreign(['salary_component_id'])->references(['id'])->on('salary_components')->onUpdate('restrict')->onDelete('cascade');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('payroll_user_salary_components', function (Blueprint $table) {
            $table->dropForeign('payroll_user_salary_components_payroll_user_id_foreign');
            $table->dropForeign('payroll_user_salary_components_salary_component_id_foreign');
        });
    }
};
