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
        Schema::create('transactions', function (Blueprint $table) {
            $table->id();
            $table->foreignId('account_id')->constrained('accounts')->onDelete('cascade');
            $table->enum('type', ['cash_in', 'cash_out']);
            $table->unsignedBigInteger('amount');
            $table->unsignedBigInteger('fee_amount');
            $table->enum('fee_type', ['exact', 'net']);
            $table->unsignedBigInteger('total_cash_impact');
            $table->unsignedBigInteger('total_emoney_impact');
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
