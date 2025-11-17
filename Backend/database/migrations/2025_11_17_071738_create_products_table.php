<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateProductsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('products', function (Blueprint $table) {
            $table->id();
            $table->string('name');
            $table->text('description')->nullable();
            $table->enum('category', ['seeds', 'fertilizers', 'equipment', 'tools']);
            $table->decimal('price', 10, 2);
            $table->string('currency', 3)->default('USD');
            $table->string('image_url')->nullable();
            $table->integer('stock_quantity')->default(0);
            $table->enum('status', ['active', 'inactive', 'out_of_stock'])->default('active');
            $table->timestamps();
            $table->softDeletes();

            // Indexes
            $table->index('category');
            $table->index('status');
            // Full-text index for search (requires MySQL 5.6+ with InnoDB)
            $table->fullText(['name', 'description']);
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('products');
    }
}
