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
        Schema::create('invoice_items', function (Blueprint $table) {
            $table->id();
            $table->foreignId('product_id')->nullable()->constrained('products')->onDelete('set null');
            $table->foreignId('invoice_id')->nullable()->constrained('invoices')->onDelete('set null');
            $table->integer('quantity');
            $table->decimal('price', 10, 2);
            $table->decimal('total', 10, 2);
            $table->text('delete_flag')->default(0);
            $table->integer('returned_quantity')->default(0);
            $table->decimal('discount', 5, 2)->default(0); // ❌ removed ->after('price')
            $table->decimal('final_price', 10, 2)->default(0); // ❌ removed ->after('discount')
            $table->unsignedBigInteger('grn_item_id')->nullable(); // ❌ removed ->after('product_id')
            $table->foreign('grn_item_id')->references('id')->on('g_r_n_items')->onDelete('cascade');
            $table->timestamps();
        });
        
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('invoice_items');
    }
};
