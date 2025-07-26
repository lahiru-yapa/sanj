<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up()
{
    Schema::create('expenses', function (Blueprint $table) {
        $table->id();
        $table->unsignedBigInteger('warehouse_id')->nullable(); // If specific to warehouse
        $table->unsignedBigInteger('user_id')->nullable(); // If specific to warehouse
        $table->decimal('amount', 10, 2);
        $table->date('expense_date');
        $table->string('category')->nullable(); // optional (e.g., Invoice #, Ref No)
         $table->string('expense_type')->nullable();
        $table->text('note')->nullable();
        $table->unsignedBigInteger('created_by');
        $table->timestamps();
        $table->string('paid_to')->nullable(); // Name of the person or entity

        $table->foreign('warehouse_id')->references('id')->on('warehouses');
    });
}

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('expenses');
    }
};
