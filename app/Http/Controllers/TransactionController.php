<?php

namespace App\Http\Controllers;

use App\DataTables\TransactionDataTable;
use App\Enums\FeeType;
use App\Enums\TransactionType;
use App\Exports\TransactionExport;
use App\Filters\TransactionFilter;
use App\Http\Requests\Transaction\StoreRequest;
use App\Models\Account;
use App\Services\TransactionService;
use Illuminate\Foundation\Auth\Access\AuthorizesRequests;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\View\View;
use Maatwebsite\Excel\Facades\Excel;

class TransactionController extends Controller
{
    use AuthorizesRequests;

    private const BASE_PATH = 'admin.transactions.';

    public function __construct(
        private readonly TransactionService $service
    ) {}

    public function index(): View
    {
        $this->authorize('has-permission', 'transaction-list');

        return view(self::BASE_PATH . 'index', [
            'accounts' => $this->accountOptions(),
            'types' => TransactionType::options(),
            'feeTypes' => FeeType::options(),
            'emoneyTotal' => $this->service->emoneyTotal(),
            'cashTotal' => $this->service->cashTotal(),
        ]);
    }

    public function data(TransactionDataTable $dataTable): JsonResponse
    {
        return $dataTable->ajax();
    }

    public function create(): View
    {
        $this->authorize('has-permission', 'transaction-create');

        return view(self::BASE_PATH . 'form', [
            'route' => route('transactions.store'),
            'accounts' => $this->accountOptions(),
            'types' => TransactionType::options(),
            'feeTypes' => FeeType::options(),
            'emoneyTotal' => $this->service->emoneyTotal(),
            'cashTotal' => $this->service->cashTotal(),
        ]);
    }

    public function store(StoreRequest $request): RedirectResponse
    {
        $this->authorize('has-permission', 'transaction-create');

        return $this->handleServiceCall(
            fn () => $this->service->store($request->validated()),
            'transactions.index'
        );
    }

    public function export(Request $request, TransactionFilter $filter)
    {
        $this->authorize('has-permission', 'transaction-export');
        $query = $filter->apply($this->service->getData());
        $filename = 'ငွေလွှဲငွေထုတ်မှတ်တမ်းများ_' . time() . '.xlsx';

        return Excel::download(new TransactionExport($query), $filename);
    }

    private function accountOptions(): array
    {
        return Account::query()
            ->with('bankType')
            ->orderBy('name')
            ->get()
            ->mapWithKeys(fn (Account $account) => [
                $account->id => $account->name . ' (' . ($account->bankType?->name ?? '-') . ') — ' . number_format($account->balance) . ' MMK',
            ])
            ->toArray();
    }
}
