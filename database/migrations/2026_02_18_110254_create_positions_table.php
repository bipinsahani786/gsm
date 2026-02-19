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
        Schema::create('positions', function (Blueprint $table) {
            $table->id();
            $table->string('name'); // e.g. Executive Assistant
            $table->decimal('salary', 15, 2); // e.g. 5500.00
            $table->string('team_condition'); // e.g. "20 A-level team members (150 People)"
            $table->integer('required_members')->default(0); // e.g. 20
            $table->string('icon')->nullable();
            $table->boolean('status')->default(true);
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('positions');
    }
};
