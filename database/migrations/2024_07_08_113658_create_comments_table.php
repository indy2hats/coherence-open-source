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
        Schema::create('comments', function (Blueprint $table) {
            $table->bigIncrements('id');
            $table->unsignedBigInteger('commenter_id')->nullable();
            $table->string('commenter_type', 191)->nullable();
            $table->string('guest_name', 191)->nullable();
            $table->string('guest_email', 191)->nullable();
            $table->string('commentable_type', 191);
            $table->unsignedBigInteger('commentable_id');
            $table->longText('comment');
            $table->boolean('approved')->default(true);
            $table->unsignedBigInteger('child_id')->nullable()->index('comments_child_id_foreign');
            $table->softDeletes();
            $table->timestamps();

            $table->index(['commentable_type', 'commentable_id']);
            $table->index(['commenter_id', 'commenter_type']);
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('comments');
    }
};
