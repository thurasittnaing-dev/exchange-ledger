<?php

namespace App\Http\Controllers;

use App\DataTables\CashMoneyDataTable;
use App\Enums\CashHistoryReferenceType;
use App\Exports\CashMoneyExport;
use App\Filters\CashMoneyFilter;
use App\Http\Requests\CashMoney\StoreRequest;
use App\Services\CashMoneyService;
use Illuminate\Foundation\Auth\Access\AuthorizesRequests;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\View\View;
use Maatwebsite\Excel\Facades\Excel;

class CashMoneyController extends Controller
{
    use AuthorizesRequests;

    private const BASE_PATH = 'admin.cash_money.';

    public function __construct(
        private readonly CashMoneyService $service
    ) {}

    public function index(): View
    {
        $this->authorize('has-permission', 'cash-money-list');

        return view(self::BASE_PATH . 'index', [
            'currentBalance' => $this->service->currentBalance(),
            'referenceTypes' => CashHistoryReferenceType::manualOptions(),
        ]);
    }

    public function data(CashMoneyDataTable $dataTable): JsonResponse
    {
        return $dataTable->ajax();
    }

    public function create(): View
    {
        $this->authorize('has-permission', 'cash-money-create');

        return view(self::BASE_PATH . 'form', [
            'route' => route('cash_money.store'),
            'currentBalance' => $this->service->currentBalance(),
            'referenceTypes' => CashHistoryReferenceType::manualOptions(),
        ]);
    }

    public function store(StoreRequest $request): RedirectResponse
    {
        $this->authorize('has-permission', 'cash-money-create');

        return $this->handleServiceCall(
            fn () => $this->service->store($request->validated()),
            'cash_money.index'
        );
    }

    public function export(Request $request, CashMoneyFilter $filter)
    {
        $this->authorize('has-permission', 'cash-money-export');
        $query = $filter->apply($this->service->getData());
        $filename = 'ငွေသားလက်ကျန်မှတ်တမ်းများ_' . time() . '.xlsx';

        return Excel::download(new CashMoneyExport($query), $filename);
    }
}
