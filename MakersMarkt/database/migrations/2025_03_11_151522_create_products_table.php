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
        Schema::create('products', function (Blueprint $table) {
            $table->id('product_id');
            $table->string('name');
            $table->text('description');
            $table->string('type');
            $table->string('material');
            $table->integer('production_time'); // in days
            $table->string('complexity');
            $table->string('durability');
            $table->text('unique_features')->nullable();
            $table->boolean('contains_external_links')->default(false);
            $table->unsignedBigInteger('maker_id');
            $table->timestamps();

            $table->foreign('maker_id')
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
        Schema::dropIfExists('products');
    }
};