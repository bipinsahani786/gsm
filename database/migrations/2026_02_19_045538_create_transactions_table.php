<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration {
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        Schema::create('transactions', function (Blueprint $table) {
            $table->id();
            $table->foreignId('user_id')->constrained()->onDelete('cascade');
            $table->decimal('amount', 15, 2);
            $table->decimal('post_balance', 15, 2); // Updated balance
            $table->string('type'); // deposit, withdrawal, bet, win, bonus
            $table->string('trx_id')->unique(); // Unique Transaction ID (e.g., TRX12345678)
            $table->string('details'); // Short description (e.g., "Recharge via UPI")
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('transactions');
    }
};
