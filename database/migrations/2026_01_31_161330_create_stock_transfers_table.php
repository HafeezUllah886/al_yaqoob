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
        Schema::create('stock_transfers', function (Blueprint $table) {
            $table->id();
            $table->date('date');
            $table->foreignId('branch_from_id')->constrained('branches', 'id');
            $table->foreignId('branch_to_id')->constrained('branches', 'id');
            $table->foreignId('product_id')->constrained('products', 'id');
            $table->foreignId('unit_id')->constrained('product_units', 'id');
            $table->float('unit_value')->default(0);
            $table->float('pcs')->default(0);
            $table->foreignId('user_id')->constrained('users', 'id');
            $table->bigInteger('refID');
            $table->string('notes')->nullable();
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('stock_transfers');
    }
};
