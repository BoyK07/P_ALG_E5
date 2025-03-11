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
        Schema::create('reviews', function (Blueprint $table) {
            $table->id('review_id');
            $table->unsignedBigInteger('order_id');
            $table->integer('rating');
            $table->text('comment')->nullable();
            $table->date('review_date');
            $table->timestamps();

            $table->foreign('order_id')
                ->references('order_id')
                ->on('orders')
                ->onDelete('cascade');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('reviews');
    }
};