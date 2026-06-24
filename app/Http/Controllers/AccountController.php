<?php

namespace App\Http\Controllers;

use App\DataTables\AccountDataTable;
use App\Enums\Provider;
use App\Exports\AccountExport;
use App\Filters\AccountFilter;
use App\Http\Requests\Account\StoreRequest;
use App\Http\Requests\Account\UpdateRequest;
use App\Models\Account;
use App\Models\BankType;
use App\Services\AccountService;
use Illuminate\Foundation\Auth\Access\AuthorizesRequests;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\View\View;
use Maatwebsite\Excel\Facades\Excel;

class AccountController extends Controller
{
    use AuthorizesRequests;

    private const BASE_PATH = 'admin.accounts.';

    public function __construct(
        private readonly AccountService $service
    ) {}

    public function index(): View
    {
        $this->authorize('has-permission', 'account-list');

        return view(self::BASE_PATH . 'index', [
            'bankTypes' => $this->bankTypeOptions(),
            'providers' => Provider::options(),
        ]);
    }

    public function data(AccountDataTable $dataTable): JsonResponse
    {
        return $dataTable->ajax();
    }

    public function create(): View
    {
        $this->authorize('has-permission', 'account-create');

        return view(self::BASE_PATH . 'form', [
            'route' => route('accounts.store'),
            'method' => 'POST',
            'account' => null,
            'btnLabel' => 'Create Account',
            'bankTypes' => $this->bankTypeOptions(),
        ]);
    }

    public function store(StoreRequest $request): RedirectResponse
    {
        $this->authorize('has-permission', 'account-create');

        return $this->handleServiceCall(
            fn () => $this->service->store($request->validated()),
            'accounts.index'
        );
    }

    public function edit(Account $account): View
    {
        $this->authorize('has-permission', 'account-edit');

        return view(self::BASE_PATH . 'form', [
            'route' => route('accounts.update', $account),
            'method' => 'PUT',
            'account' => $account,
            'btnLabel' => 'Update Account',
            'bankTypes' => $this->bankTypeOptions(),
        ]);
    }

    public function update(UpdateRequest $request, Account $account): RedirectResponse
    {
        $this->authorize('has-permission', 'account-edit');

        return $this->handleServiceCall(
            fn () => $this->service->update($account, $request->validated()),
            'accounts.index'
        );
    }

    public function destroy(Account $account): RedirectResponse
    {
        $this->authorize('has-permission', 'account-delete');

        return $this->handleServiceCall(
            fn () => $this->service->delete($account),
            'accounts.index'
        );
    }

    public function export(Request $request, AccountFilter $filter)
    {
        $this->authorize('has-permission', 'account-export');
        $query = $filter->apply($this->service->getData());
        $filename = 'အကောင့်များ_' . time() . '.xlsx';

        return Excel::download(new AccountExport($query), $filename);
    }

    private function bankTypeOptions(): array
    {
        return BankType::query()
            ->orderBy('name')
            ->pluck('name', 'id')
            ->toArray();
    }
}
