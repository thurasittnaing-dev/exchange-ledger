<?php

namespace App\Providers;

use App\Interfaces\AccountBalanceHistoryRepositoryInterface;
use App\Interfaces\AccountRepositoryInterface;
use App\Interfaces\ActivityGroupRepositoryInterface;
use App\Interfaces\BankTypeRepositoryInterface;
use App\Interfaces\CashMoneyRepositoryInterface;
use App\Interfaces\DistrictRepositoryInterface;
use App\Interfaces\DepartmentTypeRepositoryInterface;
use App\Interfaces\DivisionRepositoryInterface;
use App\Interfaces\ExpenseCategoryRepositoryInterface;
use App\Interfaces\PermissionRepositoryInterface;
use App\Interfaces\SubstanceRepositoryInterface;
use App\Interfaces\UserRepositoryInterface;
use App\Interfaces\WorkPlanActionRepositoryInterface;
use App\Interfaces\WorkPlanRepositoryInterface;
use App\Models\Management\User;
use App\Models\Master\ActivityGroup;
use App\Models\Master\Department;
use App\Models\Master\Substance;
use App\Repositories\AccountBalanceHistoryRepository;
use App\Repositories\AccountRepository;
use App\Repositories\ActivityGroupRepository;
use App\Repositories\BankTypeRepository;
use App\Repositories\CashMoneyRepository;
use App\Repositories\DistrictRepository;
use App\Repositories\DepartmentTypeRepository;
use App\Repositories\DivisionRepository;
use App\Repositories\ExpenseCategoryRepository;
use App\Repositories\PermissionRepository;
use App\Repositories\SubstanceRepository;
use App\Repositories\UserRepository;
use App\Repositories\WorkPlanActionRepository;
use App\Repositories\WorkPlanRepository;
use Illuminate\Support\Facades\Gate;
use Illuminate\Support\Facades\Route;
use Illuminate\Support\ServiceProvider;

class AppServiceProvider extends ServiceProvider
{
    /**
     * Register any application services.
     */
    public function register(): void
    {
        $this->app->bind(UserRepositoryInterface::class, UserRepository::class);
        $this->app->bind(PermissionRepositoryInterface::class, PermissionRepository::class);
        $this->app->bind(DivisionRepositoryInterface::class, DivisionRepository::class);
        $this->app->bind(DistrictRepositoryInterface::class, DistrictRepository::class);
        $this->app->bind(DepartmentTypeRepositoryInterface::class, DepartmentTypeRepository::class);
        $this->app->bind(SubstanceRepositoryInterface::class, SubstanceRepository::class);
        $this->app->bind(ExpenseCategoryRepositoryInterface::class, ExpenseCategoryRepository::class);
        $this->app->bind(ActivityGroupRepositoryInterface::class, ActivityGroupRepository::class);
        $this->app->bind(WorkPlanRepositoryInterface::class, WorkPlanRepository::class);
        $this->app->bind(WorkPlanActionRepositoryInterface::class, WorkPlanActionRepository::class);
        $this->app->bind(BankTypeRepositoryInterface::class, BankTypeRepository::class);
        $this->app->bind(AccountRepositoryInterface::class, AccountRepository::class);
        $this->app->bind(AccountBalanceHistoryRepositoryInterface::class, AccountBalanceHistoryRepository::class);
        $this->app->bind(CashMoneyRepositoryInterface::class, CashMoneyRepository::class);
    }

    /**
     * Bootstrap any application services.
     */
    public function boot(): void
    {
        Route::bind('department_type', fn ($value) => Department::findOrFail($value));
        Route::bind('substance', fn ($value) => Substance::findOrFail($value));
        Route::bind('activity_group', fn ($value) => ActivityGroup::findOrFail($value));

        Gate::define('has-permission', function (User $user, ...$permissions) {
            foreach ($permissions as $permission) {
                if ($user->checkPermission($permission)) {
                    return true;
                }
            }
            return false;
        });
    }
}
