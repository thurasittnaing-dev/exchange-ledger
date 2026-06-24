<?php

use App\Http\Controllers\ActivityGroupController;
use App\Http\Controllers\AccountBalanceHistoryController;
use App\Http\Controllers\AccountController;
use App\Http\Controllers\BankTypeController;
use App\Http\Controllers\CashMoneyController;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\DepartmentTypeController;
use App\Http\Controllers\DistrictController;
use App\Http\Controllers\DivisionController;
use App\Http\Controllers\ExpenseCategoryController;
use App\Http\Controllers\PermissionController;
use App\Http\Controllers\SubstanceController;

// Permission Management
Route::resource('permissions', PermissionController::class)->except('show');
Route::prefix('permissions')->name('permissions.')->group(function () {
    Route::get('/data/ajax', [PermissionController::class, 'data'])->name('data');
    Route::get('/export', [PermissionController::class, 'export'])->name('export');
});

//Division Management
Route::resource('divisions', DivisionController::class)->except('show');
Route::prefix('divisions')->name('divisions.')->group(function () {
    Route::get('/data/ajax', [DivisionController::class, 'data'])->name('data');
    Route::get('/export', [DivisionController::class, 'export'])->name('export');
});

//District Management
Route::prefix('districts')->name('districts.')->group(function () {
    Route::get('/filtered/{division_id}', [DistrictController::class, 'filtered'])->name('filtered');
    Route::get('/data/ajax', [DistrictController::class, 'data'])->name('data');
    Route::get('/export', [DistrictController::class, 'export'])->name('export');
});
Route::resource('districts', DistrictController::class)->except('show');

// Department Management
Route::resource('department_types', DepartmentTypeController::class)->except('show');
Route::prefix('department_types')->name('department_types.')->group(function () {
    Route::get('/data/ajax', [DepartmentTypeController::class, 'data'])->name('data');
    Route::get('/export', [DepartmentTypeController::class, 'export'])->name('export');

    Route::get('/sub-department/data', [DepartmentTypeController::class, 'subData'])
        ->name('sub_departments.data');

    Route::get('/sub-department/{department_type}', [DepartmentTypeController::class, 'addSubDepartments'])
        ->name('add_sub_departments');

    Route::post('/sub-department/{department_type}', [DepartmentTypeController::class, 'storeSubDepartments'])
        ->name('store_sub_departments');
});

// Substance Management
Route::resource('substances', SubstanceController::class)->except('show');
Route::prefix('substances')->name('substances.')->group(function () {
    Route::get('/data/ajax', [SubstanceController::class, 'data'])->name('data');
    Route::get('/export', [SubstanceController::class, 'export'])->name('export');

    Route::get('/sub-substance/data', [SubstanceController::class, 'subData'])
        ->name('sub_substances.data');

    Route::get('/sub-substance/{substance}', [SubstanceController::class, 'addSubSubstances'])
        ->name('add_sub_substances');

    Route::post('/sub-substance/{substance}', [SubstanceController::class, 'storeSubSubstances'])
        ->name('store_sub_substances');
});

//Expense Category Management
Route::resource('expense_categories', ExpenseCategoryController::class)->except('show');
Route::prefix('expense_categories')->name('expense_categories.')->group(function () {

    Route::get('/data/ajax', [ExpenseCategoryController::class, 'data'])->name('data');
    Route::get('/export', [ExpenseCategoryController::class, 'export'])->name('export');

    Route::get('/sub-category/data', [ExpenseCategoryController::class, 'subData'])
        ->name('sub_expense_categories.data');

    Route::get('/sub-category/{expense_category}', [ExpenseCategoryController::class, 'addSubExpenseCategories'])
        ->name('add_sub_expense_categories');

    Route::post('/sub-category/{expense_category}', [ExpenseCategoryController::class, 'storeSubExpenseCategories'])
        ->name('store_sub_expense_categories');

    Route::post('/expense-categories/unlock', [ExpenseCategoryController::class, 'unlockAction'])
        ->name('unlock_expense_categories');
});

// Activity Group Management
Route::prefix('activity_groups')->name('activity_groups.')->group(function () {
    Route::get('/sub-activity-group/data', [ActivityGroupController::class, 'subData'])
        ->name('sub_activity_groups.data');

    Route::get('/sub-activity-group/{activity_group}', [ActivityGroupController::class, 'addSubActivityGroups'])
        ->name('add_sub_activity_groups');

    Route::post('/sub-activity-group/{activity_group}', [ActivityGroupController::class, 'storeSubActivityGroups'])
        ->name('store_sub_activity_groups');

    Route::get('/data/ajax', [ActivityGroupController::class, 'data'])->name('data');
    Route::get('/export', [ActivityGroupController::class, 'export'])->name('export');
});
Route::resource('activity_groups', ActivityGroupController::class)->except('show');

// Bank Type Management
Route::resource('bank_types', BankTypeController::class)->except('show');
Route::prefix('bank_types')->name('bank_types.')->group(function () {
    Route::get('/data/ajax', [BankTypeController::class, 'data'])->name('data');
    Route::get('/export', [BankTypeController::class, 'export'])->name('export');
});

// Account Management
Route::resource('accounts', AccountController::class)->except('show');
Route::prefix('accounts')->name('accounts.')->group(function () {
    Route::get('/data/ajax', [AccountController::class, 'data'])->name('data');
    Route::get('/export', [AccountController::class, 'export'])->name('export');
});

// Account Balance History (Manual Add / Reset)
Route::prefix('account_balance_histories')->name('account_balance_histories.')->group(function () {
    Route::get('/', [AccountBalanceHistoryController::class, 'index'])->name('index');
    Route::get('/create', [AccountBalanceHistoryController::class, 'create'])->name('create');
    Route::post('/', [AccountBalanceHistoryController::class, 'store'])->name('store');
    Route::get('/data/ajax', [AccountBalanceHistoryController::class, 'data'])->name('data');
    Route::get('/export', [AccountBalanceHistoryController::class, 'export'])->name('export');
});

// Cash Money (Opening / Reset)
Route::prefix('cash_money')->name('cash_money.')->group(function () {
    Route::get('/', [CashMoneyController::class, 'index'])->name('index');
    Route::get('/create', [CashMoneyController::class, 'create'])->name('create');
    Route::post('/', [CashMoneyController::class, 'store'])->name('store');
    Route::get('/data/ajax', [CashMoneyController::class, 'data'])->name('data');
    Route::get('/export', [CashMoneyController::class, 'export'])->name('export');
});
