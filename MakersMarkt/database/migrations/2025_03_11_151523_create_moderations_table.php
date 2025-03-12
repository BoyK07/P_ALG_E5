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
        Schema::create('moderations', function (Blueprint $table) {
            $table->id('moderation_id');
            $table->unsignedBigInteger('product_id');
            $table->unsignedBigInteger('moderator_id');
            $table->text('reason');
            $table->string('action_taken');
            $table->date('moderation_date');
            $table->timestamps();

            $table->foreign('product_id')
                ->references('product_id')
                ->on('products')
                ->onDelete('cascade');

            $table->foreign('moderator_id')
                ->references('id')
                ->on('users')
                ->onDelete('cascade');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('moderations');
    }
};