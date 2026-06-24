<?php

declare(strict_types=1);

namespace App\Services;

use App\Interfaces\ExpenseCategoryRepositoryInterface;
use App\Models\Master\ExpenseCategory;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Log;


final class ExpenseCategoryService
{
    public function __construct(
        private readonly ExpenseCategoryRepositoryInterface $repository
    ) {}

    public function getData(): Builder
    {
        return $this->repository->getData();
    }

    public function store(array $data): array
    {
        try {
            $this->repository->createExpenseCategory($data);
            return [
                'status' => true,
                'message' => 'Expense Category created successfully'
            ];
        } catch (\Exception $e) {
            showError($e, "Expense Category Error: ");
            return [
                'status' => false,
                'message' => 'Something went wrong during Expense Category creation'
            ];
        }
    }

    public function update(ExpenseCategory $expenseCategory, array $data): array
    {
        try {
            $this->repository->updateExpenseCategory($expenseCategory, $data);
            return [
                'status' => true,
                'message' => 'Expense Category updated successfully.'
            ];
        } catch (\Exception $e) {
            showError($e, "Expense Category Update Error: ");
            return [
                'status' => false,
                'message' => 'Something went wrong during Expense Category update.'
            ];
        }
    }

    public function delete(ExpenseCategory $expenseCategory): array
    {
        try {
            $this->repository->deleteExpenseCategory($expenseCategory);
            return [
                'status' => true,
                'message' => 'Expense Category deleted successfully.'
            ];
        } catch (\Exception $e) {
            showError($e, "Expense Category Delete Error: ");
            return [
                'status' => false,
                'message' => 'Something went wrong during Division delete.'
            ];
        }
    }
    public function storeSubCategory(array $data, ExpenseCategory $expenseCategory): array
    {
        try {
            $this->repository->createSubExpenseCategory($data, $expenseCategory);
            return [
                'status' => true,
                'message' => 'Sub Expense Category created successfully'
            ];
        } catch (\Exception $e) {
            showError($e, "Sub Expense Category Error: ");
            return [
                'status' => false,
                'message' => 'Something went wrong during Sub Expense Category creation'
            ];
        }
    }

    public function verifyAndUnlock(string $password, ExpenseCategory $category): array
    {
        $correctPassword = config('custom.action_password');

        try {
            if ($password === $correctPassword) {
                Log::info("User [" . auth()->user()->full_name . "] unlocked expense category name: " . $category->name . " (Code: " . $category->code . ")");
                return [
                    'status' => true,
                    'message' => 'Unlock Successfully'
                ];
            }
            return [
                'status' => false,
                'message' => 'Wrong Password.',
                'code' => 422
            ];

        } catch (\Exception $e) {
            showError($e, "Unlock Error: " );
            return [
                'status' => false,
                'message' => 'Something went wrong during Unlock'
            ];
        }
    }
}
