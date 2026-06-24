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
        Schema::create('cash_histories', function (Blueprint $table) {
            $table->id();
            $table->enum('reference_type', ['transaction', 'opening', 'reset']);
            $table->unsignedBigInteger('reference_id')->nullable();
            $table->enum('action', ['credit', 'debit', 'reset']);
            $table->unsignedBigInteger('amount');
            $table->unsignedBigInteger('running_balance');
            $table->text('description')->nullable();
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('cash_histories');
    }
};
