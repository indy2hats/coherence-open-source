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
        Schema::table('credential_assigned_users', function (Blueprint $table) {
            $table->foreign(['credential_id'])->references(['id'])->on('project_credentials')->onUpdate('restrict')->onDelete('cascade');
            $table->foreign(['user_id'])->references(['id'])->on('users')->onUpdate('restrict')->onDelete('cascade');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('credential_assigned_users', function (Blueprint $table) {
            $table->dropForeign('credential_assigned_users_credential_id_foreign');
            $table->dropForeign('credential_assigned_users_user_id_foreign');
        });
    }
};
