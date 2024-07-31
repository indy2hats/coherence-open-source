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
        Schema::create('users', function (Blueprint $table) {
            $table->bigIncrements('id');
            $table->string('first_name', 191);
            $table->string('last_name', 191)->nullable();
            $table->string('email', 191)->unique();
            $table->string('password', 191);
            $table->string('employee_id', 191)->nullable();
            $table->date('joining_date');
            $table->date('rejoin_date')->nullable()->default(null);
            $table->string('phone', 191)->nullable();
            $table->text('address')->nullable();
            $table->unsignedBigInteger('department_id')->nullable()->index('users_department_id_foreign');
            $table->unsignedBigInteger('designation_id')->nullable()->index('users_designation_id_foreign');
            $table->unsignedBigInteger('role_id');
            $table->unsignedBigInteger('monthly_salary');
            $table->string('nick_name', 191)->nullable();
            $table->string('image_path', 191)->nullable();
            $table->rememberToken();
            $table->text('email_token')->nullable();
            $table->timestamp('email_token_expired_at')->nullable();
            $table->tinyInteger('must_change_password')->default(1);
            $table->boolean('wish_notify')->default(true);
            $table->timestamps();
            $table->tinyInteger('status');
            $table->tinyInteger('contract')->default(0);
            $table->tinyInteger('dsr_notify')->default(0);
            $table->tinyInteger('dsr_late_notify')->default(0);
            $table->date('dsr_late_date')->nullable();
            $table->longText('easy_access');
            $table->string('gender', 191);
            $table->date('leaving_date')->nullable();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('users');
    }
};
