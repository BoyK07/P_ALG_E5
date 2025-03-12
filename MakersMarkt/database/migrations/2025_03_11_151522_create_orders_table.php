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
        Schema::create('orders', function (Blueprint $table) {
            $table->id('order_id');
            $table->unsignedBigInteger('buyer_id');
            $table->unsignedBigInteger('product_id');
            $table->decimal('store_credit_used', 10, 2)->default(0);
            $table->enum('status', ['pending', 'processing', 'shipped', 'delivered', 'cancelled']);
            $table->text('status_description')->nullable();
            $table->date('order_date');
            $table->timestamps();

            $table->foreign('buyer_id')
                ->references('id')
                ->on('users')
                ->onDelete('cascade');

            $table->foreign('product_id')
                ->references('product_id')
                ->on('products')
                ->onDelete('cascade');
        });

        Schema::create('order_status_history', function (Blueprint $table) {
            $table->id('history_id');
            $table->unsignedBigInteger('order_id');
            $table->string('old_status');
            $table->string('new_status');
            $table->timestamp('changed_at');
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
        Schema::dropIfExists('order_status_history');
        Schema::dropIfExists('orders');
    }
};