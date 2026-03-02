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
        Schema::create('non_business_expenses', function (Blueprint $table) {
            $table->id();
            $table->foreignId('account_id')->constrained('accounts', 'id');
            $table->foreignId('category_id')->constrained('non_business_expense_categories', 'id');
            $table->foreignId('branch_id')->constrained('branches', 'id');
            $table->date('date');
            $table->float('amount');
            $table->text('notes')->nullable();
            $table->string('source')->default('Non-Business Expense');
            $table->bigInteger('refID');
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('non_business_expenses');
    }
};
