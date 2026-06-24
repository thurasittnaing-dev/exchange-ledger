<?php

namespace App\Http\Controllers;

use App\DataTables\ExpenseCategoryDataTable;
use App\DataTables\SubExpenseCategoryDataTable;
use App\Enums\ExpenseType;
use App\Exports\ExpenseCategoryExport;
use App\Filters\ExpenseCategoryFilter;
use App\Http\Requests\ExpenseCategory\StoreRequest;
use App\Models\Master\ExpenseCategory;
use App\Services\ExpenseCategoryService;
use Illuminate\Foundation\Auth\Access\AuthorizesRequests;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\View\View;
use Maatwebsite\Excel\Facades\Excel;

class ExpenseCategoryController extends Controller
{
    use AuthorizesRequests;

    private const BASE_PATH = 'admin.expense_categories.';

    public function __construct(
        private readonly ExpenseCategoryService $service
    ) {}

    public function index(): View
    {
        $this->authorize('has-permission', 'expense-category-list');
        return view(self::BASE_PATH . 'index');
    }

    public function data(ExpenseCategoryDataTable $dataTable): JsonResponse
    {
        return $dataTable->ajax();
    }

    public function create(): View
    {
        $this->authorize('has-permission', 'expense-category-create');
        return view(self::BASE_PATH . 'form', [
            'route' => route('expense_categories.store'),
            'method' => 'POST',
            'expenseCategory' => null,
            'btnLabel' => 'Create',
            'expenseTypes' => ExpenseType::options(),
        ]);
    }

    public function store(StoreRequest $request): RedirectResponse
    {
        $this->authorize('has-permission', 'expense-category-create');
        return $this->handleServiceCall(
            fn() => $this->service->store($request->validated()),
            'expense_categories.index'
        );
    }

    public function edit(Request $request, ExpenseCategory $expenseCategory): View
    {
        $this->authorize('has-permission', 'expense-category-edit');
        if (!isActionUnlocked($expenseCategory->id)) {
            return view(self::BASE_PATH . 'index');
        }
        return view(self::BASE_PATH . 'form', [
            'route' => route('expense_categories.update', $expenseCategory),
            'method' => 'PUT',
            'expenseCategory' => $expenseCategory,
            'btnLabel' => 'Update',
            'expenseTypes' => ExpenseType::options(),
        ]);
    }

    public function update(StoreRequest $request, ExpenseCategory $expenseCategory): RedirectResponse
    {
        $this->authorize('has-permission', 'expense-category-edit');
        if (!isActionUnlocked($expenseCategory->id)) {
            return redirect()->route(self::BASE_PATH . 'index');
        }
        return $this->handleServiceCall(
            fn() => $this->service->update($expenseCategory, $request->validated()),
            'expense_categories.index'
        );
    }

    public function destroy(ExpenseCategory $expenseCategory): RedirectResponse
    {
        $this->authorize('has-permission', 'expense-category-delete');
        if (!isActionUnlocked($expenseCategory->id)) {
            return redirect()->route(self::BASE_PATH . 'index');
        }
        return $this->handleServiceCall(
            fn() => $this->service->delete($expenseCategory),
            'expense_categories.index'
        );
    }

    public function export(Request $request, ExpenseCategoryFilter $filter)
    {
        $this->authorize('has-permission', 'expense-category-export');
        $query = $filter->apply($this->service?->getData())->whereNull('parent_id');
        $filename = 'အထွေထွေကုန်ကျစရိတ်များ_' . time() . '.xlsx';
        return Excel::download(new ExpenseCategoryExport($query), $filename);
    }
    public function addSubExpenseCategories(ExpenseCategory $expenseCategory): View
    {
        if (!isActionUnlocked($expenseCategory->id)) {
             return view(self::BASE_PATH . 'index');
        }
        $subCategories = $expenseCategory->children()->latest()->get();

        return view(self::BASE_PATH . 'add_sub_expense_category', [
            'route' => route(
                'expense_categories.store_sub_expense_categories',
                $expenseCategory
            ),
            'method' => 'POST',
            'expenseCategory' => $expenseCategory,
            'subExpenseCategory' => null,
            'subCategories' => $subCategories,
            'btnLabel' => 'Create Sub Category',
            'expenseTypes' => ExpenseType::options(),
        ]);
    }
    public function storeSubExpenseCategories(StoreRequest $request, ExpenseCategory $expenseCategory): RedirectResponse
    {
        if (!isActionUnlocked($expenseCategory->id)) {
            return redirect()->route('expense_categories.index');
        }
        $this->service->storeSubCategory($request->validated(), $expenseCategory);

        return redirect()->route('expense_categories.add_sub_expense_categories', $expenseCategory->id)->with('success', 'Sub Expense Category Created Successfully.');
    }
    // public function subCategories(ExpenseCategory $expenseCategory)
    // {
    //     $subCategories = $expenseCategory->children;

    //       return view(self::BASE_PATH . 'index');
    // }
    public function subData(SubExpenseCategoryDataTable $dataTable)
    {
        return $dataTable->ajax();
    }

    public function unlockAction(Request $request): JsonResponse
    {
        $request->validate([
            'id' => 'required|integer',
            'password' => 'required|string'
        ]);

        $category = ExpenseCategory::findOrFail($request->id);
        $result = $this->service->verifyAndUnlock($request->password, $category);

        if ($result['status']) {
            $sessionTime = config('custom.action_time');
            $minutes = (int) ($sessionTime ?? 5);
            $sessionKey = 'action_unlocked_until.' . $request->id;

            session([$sessionKey => now()->addMinutes($minutes)]);

            return response()->json([
                'success' => true,
                'message' => $result['message'] ?? 'Unlock Successfully'
            ]);
        }
        return response()->json([
            'success' => false,
            'message' => $result['message']
        ], $result['code'] ?? 422);
    }

}
