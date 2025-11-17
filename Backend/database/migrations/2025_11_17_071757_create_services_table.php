<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateServicesTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('services', function (Blueprint $table) {
            $table->id();
            $table->string('service_id')->unique();
            $table->string('name');
            $table->text('description')->nullable();
            $table->string('category');
            $table->string('icon')->nullable();
            $table->decimal('price', 10, 2)->nullable();
            $table->enum('price_type', ['fixed', 'monthly', 'hourly', 'per_unit'])->nullable();
            $table->integer('active_clients')->default(0);
            $table->enum('status', ['active', 'inactive', 'pending'])->default('active');
            $table->string('image_url')->nullable();
            $table->timestamps();
            $table->softDeletes();

            // Indexes
            $table->index('category');
            $table->index('status');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('services');
    }
}
