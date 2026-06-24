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
        Schema::create('account_histories', function (Blueprint $table) {
            $table->id();
            $table->foreignId('account_id')->constrained('accounts')->onDelete('cascade');
            $table->enum('reference_type', ['transaction', 'cash_history', 'manual_add', 'manual_reset']);
            $table->unsignedBigInteger('reference_id')->nullable();
            $table->enum('action', ['credit', 'debit', 'reset']);
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
        Schema::dropIfExists('account_histories');
    }
};
