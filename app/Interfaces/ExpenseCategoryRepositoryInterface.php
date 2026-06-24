<?php

namespace App\Interfaces;

use App\Models\Master\ExpenseCategory;
use Illuminate\Database\Eloquent\Builder;

interface ExpenseCategoryRepositoryInterface
{
    public function getData(): Builder;
    public function createExpenseCategory(array $data): ExpenseCategory;
    public function updateExpenseCategory(ExpenseCategory $expenseCategory, array $data): ExpenseCategory;
    public function deleteExpenseCategory(ExpenseCategory $expenseCategory): void;
    public function createSubExpenseCategory(array $data, ExpenseCategory $expenseCategory): ExpenseCategory;
}
