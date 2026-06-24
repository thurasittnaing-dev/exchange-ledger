<?php

use App\Http\Controllers\WorkPlanActionController;
use App\Http\Controllers\WorkPlanController;
use Illuminate\Support\Facades\Route;


// Work Plans Management
Route::resource('work_plans', WorkPlanController::class);
Route::prefix('work_plans')->name('work_plans.')->group(function () {
    Route::post('/major', [WorkPlanController::class, 'storeMajor'])->name('store_major');
    Route::get('/activity-groups/{activityGroup}/sub-categories', [WorkPlanController::class, 'subActivityGroups'])
        ->name('sub_activity_groups');

    // Data
    Route::get('/data/ajax', [WorkPlanController::class, 'data'])->name('data');

    // Export
    Route::get('/export', [WorkPlanController::class, 'export'])->name('export');

    // Take Action
    Route::post('/{workPlan}/take-action', [WorkPlanActionController::class, 'takeAction'])->name('takeAction');

    // Comment
    Route::post('/{workPlan}/comment', [WorkPlanActionController::class, 'comment'])->name('comment');

    // Incomplete
    Route::post('/{workPlan}/incomplete', [WorkPlanActionController::class, 'incomplete'])->name('incomplete');

    // Incomplete
    Route::post('/{workPlan}/resubmit', [WorkPlanActionController::class, 'resubmit'])->name('resubmit');

    // Approve
    Route::post('/{workPlan}/approve', [WorkPlanActionController::class, 'approve'])->name('approve');

    // Store
    Route::post('/{workPlan}/{activityGroup}/{week}/expense', [WorkPlanActionController::class, 'addActivityGroupExpense'])->name('activity_groups.add_expense');

    // Update Expenseapprove_letter
    Route::put('/{workPlanExpense}/expense', [WorkPlanActionController::class, 'updateActivityGroupExpense'])->name('activity_groups.update_expense');

    // Delete Expense
    Route::delete('/{workPlanExpense}/delete', [WorkPlanActionController::class, 'deleteActivityGroupExpense'])->name('activity_groups.delete_expense');

    //Approve Letter
    Route::get('approve-letter/{workPlan}', [WorkPlanActionController::class, 'viewApproveLetter'])->name('approve_letter');
});
