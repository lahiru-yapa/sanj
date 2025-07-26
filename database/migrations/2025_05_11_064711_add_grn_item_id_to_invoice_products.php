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
        Schema::table('invoice_items', function (Blueprint $table) {
              $table->unsignedBigInteger('grn_item_id')->nullable()->after('product_id');
            $table->foreign('grn_item_id')->references('id')->on('grn_items')->onDelete('cascade');
        });
    }

    /**
     * Reverse the migrations.
     */
     public function down(): void
    {
        Schema::table('invoice_products', function (Blueprint $table) {
            // First, drop the foreign key constraint
            $table->dropForeign(['grn_item_id']);
            // Then drop the column
            $table->dropColumn('grn_item_id');
        });
    }
};
