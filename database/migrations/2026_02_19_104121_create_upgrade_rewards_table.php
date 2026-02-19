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
       Schema::create('upgrade_rewards', function (Blueprint $table) {
            $table->id();
            $table->unsignedBigInteger('from_level_id')->nullable(); 
            $table->unsignedBigInteger('to_level_id');
            $table->decimal('reward_amount', 15, 2);
            $table->dateTime('start_date');
            $table->dateTime('end_date');
            $table->boolean('status')->default(1); // 1 = Active, 0 = Inactive
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('upgrade_rewards');
    }
};
