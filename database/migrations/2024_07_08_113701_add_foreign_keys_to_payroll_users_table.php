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
        Schema::table('payroll_users', function (Blueprint $table) {
            $table->foreign(['payroll_id'])->references(['id'])->on('payrolls')->onUpdate('restrict')->onDelete('cascade');
            $table->foreign(['user_id'])->references(['id'])->on('users')->onUpdate('restrict')->onDelete('cascade');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('payroll_users', function (Blueprint $table) {
            $table->dropForeign('payroll_users_payroll_id_foreign');
            $table->dropForeign('payroll_users_user_id_foreign');
        });
    }
};
