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
            $table->foreignId('account_id')->constrained('accounts')->onDelete('cascade');
            $table->enum('reference_type', ['transaction', 'cash_history']);
            $table->foreignId('reference_id')->constrained('transactions')->onDelete('cascade');
            $table->enum('action', ['credit', 'debit']);
            $table->unsignedBigInteger('amount');
            $table->unsignedBigInteger('running_balance');
            $table->text('description');
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
