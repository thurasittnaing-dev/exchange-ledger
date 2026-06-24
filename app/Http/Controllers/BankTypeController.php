<?php

namespace App\Http\Controllers;

use App\DataTables\BankTypeDataTable;
use App\Enums\Provider;
use App\Exports\BankTypeExport;
use App\Filters\BankTypeFilter;
use App\Http\Requests\BankType\StoreRequest;
use App\Http\Requests\BankType\UpdateRequest;
use App\Models\BankType;
use App\Services\BankTypeService;
use Illuminate\Foundation\Auth\Access\AuthorizesRequests;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\View\View;
use Maatwebsite\Excel\Facades\Excel;

class BankTypeController extends Controller
{
    use AuthorizesRequests;

    private const BASE_PATH = 'admin.bank_types.';

    public function __construct(
        private readonly BankTypeService $service
    ) {}

    public function index(): View
    {
        $this->authorize('has-permission', 'bank-type-list');
        return view(self::BASE_PATH . 'index', [
            'providers' => Provider::options(),
        ]);
    }

    public function data(BankTypeDataTable $dataTable): JsonResponse
    {
        return $dataTable->ajax();
    }

    public function create(): View
    {
        $this->authorize('has-permission', 'bank-type-create');

        return view(self::BASE_PATH . 'form', [
            'route' => route('bank_types.store'),
            'method' => 'POST',
            'bankType' => null,
            'btnLabel' => 'Create Bank Type',
            'providers' => Provider::options(),
        ]);
    }

    public function store(StoreRequest $request): RedirectResponse
    {
        $this->authorize('has-permission', 'bank-type-create');

        return $this->handleServiceCall(
            fn() => $this->service->store($request->validated()),
            'bank_types.index'
        );
    }

    public function edit(BankType $bankType): View
    {
        $this->authorize('has-permission', 'bank-type-edit');

        return view(self::BASE_PATH . 'form', [
            'route' => route('bank_types.update', $bankType),
            'method' => 'PUT',
            'bankType' => $bankType,
            'btnLabel' => 'Update Bank Type',
            'providers' => Provider::options(),
        ]);
    }

    public function update(UpdateRequest $request, BankType $bankType): RedirectResponse
    {
        $this->authorize('has-permission', 'bank-type-edit');

        return $this->handleServiceCall(
            fn() => $this->service->update($bankType, $request->validated()),
            'bank_types.index'
        );
    }

    public function destroy(BankType $bankType): RedirectResponse
    {
        $this->authorize('has-permission', 'bank-type-delete');

        return $this->handleServiceCall(
            fn() => $this->service->delete($bankType),
            'bank_types.index'
        );
    }

    public function export(Request $request, BankTypeFilter $filter)
    {
        $this->authorize('has-permission', 'bank-type-export');
        $query = $filter->apply($this->service->getData());
        $filename = 'ဘဏ်အမျိုးအစားများ_' . time() . '.xlsx';

        return Excel::download(new BankTypeExport($query), $filename);
    }
}
