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
        Schema::create('levels', function (Blueprint $table) {
            $table->id();
            $table->string('name'); // e.g. VIP 1, Silver
            $table->decimal('min_deposit', 15, 2); // e.g. 1000
            $table->integer('daily_limit'); // e.g. 10 tasks
            $table->decimal('rate', 15, 2); // Per Order Commission (e.g. ₹5)
            $table->string('icon')->nullable(); // Level Badge
            $table->boolean('status')->default(true);
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('levels');
    }
};
