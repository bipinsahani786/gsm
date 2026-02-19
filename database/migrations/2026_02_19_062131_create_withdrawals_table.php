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
        Schema::create('withdrawals', function (Blueprint $table) {
           $table->id();
        $table->foreignId('user_id')->constrained()->onDelete('cascade');
        $table->string('wallet_type'); // personal or income
        $table->string('method');      // bank or crypto
        $table->decimal('amount', 15, 2);
        $table->decimal('fee', 15, 2);    // commission amount
        $table->decimal('final_amount', 15, 2); // amount after fee     
        // Account Details
        $table->text('account_details');        
        $table->string('status')->default('pending'); // pending, approved, rejected
        $table->text('admin_remark')->nullable();
        $table->string('transaction_id')->nullable(); // payout proof
        $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('withdrawals');
    }
};
