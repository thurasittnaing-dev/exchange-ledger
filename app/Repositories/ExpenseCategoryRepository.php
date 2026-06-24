<?php

namespace App\Repositories;

use App\Interfaces\ExpenseCategoryRepositoryInterface;
use App\Models\Master\ExpenseCategory;
use Illuminate\Database\Eloquent\Builder;

class ExpenseCategoryRepository implements ExpenseCategoryRepositoryInterface
{
   public function getData(): Builder
    {
        // if(request()->has(''))
        $query = ExpenseCategory::query();
        return $query;
    }
    public function createExpenseCategory(array $data): ExpenseCategory
    {
        return ExpenseCategory::create($data);
    }
    public function updateExpenseCategory(ExpenseCategory $expenseCategory, array $data): ExpenseCategory
    {
        log_model_update($expenseCategory, $data, 'expense_category_updated');
        $expenseCategory->update($data);
        return $expenseCategory;
    }
    public function deleteExpenseCategory(ExpenseCategory $expenseCategory): void
    {
        log_model_delete($expenseCategory, 'expense_category_deleted');
        $expenseCategory->delete();
    }
    public function createSubExpenseCategory(array $data, ExpenseCategory $expenseCategory): ExpenseCategory
    {
        $data['parent_id'] = $expenseCategory->id;
        $subCategory = ExpenseCategory::create($data);
        log_model_create($subCategory, 'sub_expense_category_created');
        return $subCategory;
    }
}
