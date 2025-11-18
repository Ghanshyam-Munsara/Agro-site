<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;
use Illuminate\Support\Facades\DB;

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
        });

        // Full-text index for search (database-specific)
        $driver = DB::connection()->getDriverName();
        if ($driver === 'pgsql') {
            // PostgreSQL: Create GIN index for full-text search
            DB::statement('CREATE INDEX products_name_description_fulltext_idx ON products USING gin(to_tsvector(\'english\', coalesce(name, \'\') || \' \' || coalesce(description, \'\')));');
        } elseif ($driver === 'mysql') {
            // MySQL: Create full-text index
            DB::statement('ALTER TABLE products ADD FULLTEXT INDEX products_name_description_fulltext_idx (name, description);');
        }
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        // Drop full-text index if exists
        $driver = DB::connection()->getDriverName();
        if ($driver === 'pgsql') {
            DB::statement('DROP INDEX IF EXISTS products_name_description_fulltext_idx;');
        } elseif ($driver === 'mysql') {
            DB::statement('ALTER TABLE products DROP INDEX products_name_description_fulltext_idx;');
        }
        
        Schema::dropIfExists('products');
    }
}
