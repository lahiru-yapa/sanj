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
        Schema::create('invoices', function (Blueprint $table) {
            $table->id();
            $table->foreignId('shop_id')->constrained('shops')->onDelete('cascade');
            $table->foreignId('user_id');
            $table->foreignId('warehouse_id');
            $table->string('invoice_number');
            $table->decimal('total_amount', 10, 2);
            $table->decimal('paid_amount', 10, 2)->default(0);
            $table->decimal('remaining_balance ', 10, 2);
            $table->string('paid_status')->default('unpaid');
            $table->date('due_date');
             $table->string('discount')->nullable();
            $table->date('invoice_date');
            $table->date('payment_date')->nullable();
            $table->text('delete_flag')->default(0);// Address of the shop
            $table->text('description')->nullable();
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('invoices');
    }
    
       public function payments(): HasMany
    {
        return $this->hasMany(Payment::class);
    }
};
