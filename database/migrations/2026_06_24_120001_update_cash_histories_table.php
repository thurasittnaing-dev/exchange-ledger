<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        if (! Schema::hasTable('cash_histories')) {
            return;
        }

        Schema::table('cash_histories', function (Blueprint $table) {
            if (Schema::hasColumn('cash_histories', 'account_id')) {
                $table->dropForeign(['account_id']);
                $table->dropColumn('account_id');
            }
        });

        if ($this->foreignKeyExists('cash_histories', 'cash_histories_reference_id_foreign')) {
            Schema::table('cash_histories', function (Blueprint $table) {
                $table->dropForeign(['reference_id']);
            });
        }

        DB::statement("ALTER TABLE cash_histories MODIFY COLUMN reference_id BIGINT UNSIGNED NULL");
        DB::statement("ALTER TABLE cash_histories MODIFY COLUMN reference_type ENUM('transaction', 'opening', 'reset') NOT NULL");
        DB::statement("ALTER TABLE cash_histories MODIFY COLUMN action ENUM('credit', 'debit', 'reset') NOT NULL");

        if (Schema::hasColumn('cash_histories', 'description')) {
            DB::statement('ALTER TABLE cash_histories MODIFY COLUMN description TEXT NULL');
        }
    }

    public function down(): void
    {
        //
    }

    private function foreignKeyExists(string $table, string $foreignKey): bool
    {
        $database = Schema::getConnection()->getDatabaseName();

        $result = DB::select(
            'SELECT CONSTRAINT_NAME FROM information_schema.TABLE_CONSTRAINTS WHERE TABLE_SCHEMA = ? AND TABLE_NAME = ? AND CONSTRAINT_NAME = ? AND CONSTRAINT_TYPE = ?',
            [$database, $table, $foreignKey, 'FOREIGN KEY']
        );

        return count($result) > 0;
    }
};
