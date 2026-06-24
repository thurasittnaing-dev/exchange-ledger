<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::table('transactions', function (Blueprint $table) {
            $table->unsignedBigInteger('fee_cash_profit')->default(0)->after('total_emoney_impact');
            $table->unsignedBigInteger('fee_emoney_profit')->default(0)->after('fee_cash_profit');
            $table->text('description')->nullable()->after('fee_emoney_profit');
        });
    }

    public function down(): void
    {
        Schema::table('transactions', function (Blueprint $table) {
            $table->dropColumn(['fee_cash_profit', 'fee_emoney_profit', 'description']);
        });
    }
};
