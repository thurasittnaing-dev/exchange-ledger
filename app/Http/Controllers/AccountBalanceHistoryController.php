<?php

namespace App\Http\Controllers;

use App\DataTables\AccountBalanceHistoryDataTable;
use App\Enums\AccountHistoryReferenceType;
use App\Exports\AccountBalanceHistoryExport;
use App\Filters\AccountBalanceHistoryFilter;
use App\Http\Requests\AccountBalanceHistory\StoreRequest;
use App\Models\Account;
use App\Services\AccountBalanceHistoryService;
use Illuminate\Foundation\Auth\Access\AuthorizesRequests;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\View\View;
use Maatwebsite\Excel\Facades\Excel;

class AccountBalanceHistoryController extends Controller
{
    use AuthorizesRequests;

    private const BASE_PATH = 'admin.account_balance_histories.';

    public function __construct(
        private readonly AccountBalanceHistoryService $service
    ) {}

    public function index(): View
    {
        $this->authorize('has-permission', 'account-balance-list');

        return view(self::BASE_PATH . 'index', [
            'accounts' => $this->accountOptions(),
            'referenceTypes' => AccountHistoryReferenceType::manualOptions(),
        ]);
    }

    public function data(AccountBalanceHistoryDataTable $dataTable): JsonResponse
    {
        return $dataTable->ajax();
    }

    public function create(): View
    {
        $this->authorize('has-permission', 'account-balance-create');

        return view(self::BASE_PATH . 'form', [
            'route' => route('account_balance_histories.store'),
            'accounts' => $this->accountOptions(),
            'referenceTypes' => AccountHistoryReferenceType::manualOptions(),
        ]);
    }

    public function store(StoreRequest $request): RedirectResponse
    {
        $this->authorize('has-permission', 'account-balance-create');

        return $this->handleServiceCall(
            fn () => $this->service->store($request->validated()),
            'account_balance_histories.index'
        );
    }

    public function export(Request $request, AccountBalanceHistoryFilter $filter)
    {
        $this->authorize('has-permission', 'account-balance-export');
        $query = $filter->apply($this->service->getData());
        $filename = 'အကောင့်လက်ကျန်မှတ်တမ်းများ_' . time() . '.xlsx';

        return Excel::download(new AccountBalanceHistoryExport($query), $filename);
    }

    private function accountOptions(): array
    {
        return Account::query()
            ->with('bankType')
            ->orderBy('name')
            ->get()
            ->mapWithKeys(fn (Account $account) => [
                $account->id => $account->name . ' (' . ($account->bankType?->name ?? '-') . ')',
            ])
            ->toArray();
    }
}
