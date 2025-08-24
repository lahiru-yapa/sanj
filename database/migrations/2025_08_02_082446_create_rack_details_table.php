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
        Schema::create('rack_details', function (Blueprint $table) {
            $table->id();
            $table->string('rack_code')->unique();
            $table->string('rack_name')->nullable();
            $table->unsignedBigInteger('warehouse_id');
            $table->integer('row_number')->nullable();
            $table->integer('column_number')->nullable();
            $table->timestamps();

            $table->foreign('warehouse_id')->references('id')->on('warehouses')->onDelete('cascade');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('rack_details');
    }
};
