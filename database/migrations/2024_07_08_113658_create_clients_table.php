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
        Schema::create('clients', function (Blueprint $table) {
            $table->bigIncrements('id');
            $table->unsignedBigInteger('user_id')->nullable()->index('clients_user_id_foreign');
            $table->string('email', 191);
            $table->string('company_name', 191);
            $table->string('address', 255)->nullable();
            $table->string('phone', 191)->nullable();
            $table->string('city', 191)->nullable();
            $table->string('post_code', 191)->nullable();
            $table->string('country', 191);
            $table->string('state', 191)->nullable();
            $table->string('currency', 191)->nullable();
            $table->string('client_id', 191)->nullable();
            $table->string('vat_gst_tax_label', 191)->nullable();
            $table->string('vat_gst_tax_id', 191)->nullable();
            $table->string('vat_gst_tax_percentage', 191)->nullable();
            $table->unsignedBigInteger('account_manager_id')->nullable()->index('clients_account_manager_id_foreign');
            $table->string('image', 191)->nullable();
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('clients');
    }
};
