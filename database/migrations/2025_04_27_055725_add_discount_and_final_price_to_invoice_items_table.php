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
    $table->decimal('discount', 5, 2)->default(0)->after('price'); // Change 'price' if necessary
    $table->decimal('final_price', 10, 2)->default(0)->after('discount');


        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('invoice_items', function (Blueprint $table) {
             $table->dropColumn(['discount', 'final_price']);
        });
    }
};
