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
        Schema::create('stock_adjustments', function (Blueprint $table) {
            $table->id();
            $table->foreignId('product_id')->constrained('products', 'id');
            $table->foreignId('branch_id')->constrained('branches', 'id');
            $table->foreignId('unit_id')->constrained('product_units', 'id');
            $table->foreignId('user_id')->constrained('users', 'id');
            $table->float('unit_value');
            $table->float('qty');
            $table->string('type');
            $table->text('notes')->nullable();
            $table->date('date');
            $table->bigInteger('refID');
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('stock_adjustments');
    }
};
