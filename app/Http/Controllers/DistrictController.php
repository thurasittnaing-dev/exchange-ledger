<?php

namespace App\Http\Controllers;

use App\DataTables\DistrictDataTable;
use App\Exports\DistrictExport;
use App\Filters\DistrictFilter;
use App\Http\Requests\District\StoreRequest;
use App\Http\Requests\District\UpdateRequest;
use App\Models\Master\District;
use App\Models\Master\Division;
use App\Services\DistrictService;
use Illuminate\Foundation\Auth\Access\AuthorizesRequests;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\View\View;
use Maatwebsite\Excel\Facades\Excel;


class DistrictController extends Controller
{
    use AuthorizesRequests;

    private const BASE_PATH = 'admin.districts.';

    public function __construct(
        private readonly DistrictService $service
    ) {}

    public function index(): View
    {
        $this->authorize('has-permission', 'district-list');
        return view(self::BASE_PATH . 'index', [
            'divisions' => Division::pluck('name_mm', 'id')->toArray(),
        ]);
    }

    public function data(DistrictDataTable $dataTable): JsonResponse
    {
        return $dataTable->ajax();
    }

    public function create(): View
    {
        $this->authorize('has-permission', 'district-create');
        return view(self::BASE_PATH . 'form', [
            'route' => route('districts.store'),
            'method' => 'POST',
            'district' => null,
            'btnLabel' => 'Add District',
            'divisions' => Division::pluck('name_mm', 'id')->toArray(),
        ]);
    }

    public function store(StoreRequest $request): RedirectResponse
    {
        $this->authorize('has-permission', 'district-create');
        return $this->handleServiceCall(
            fn() => $this->service->store($request->validated()),
            'districts.index'
        );
    }

    public function edit(Request $request, District $district): View
    {
        $this->authorize('has-permission', 'district-edit');
        return view(self::BASE_PATH . 'form', [
            'route' => route('districts.update', $district),
            'method' => 'PUT',
            'district' => $district,
            'btnLabel' => 'Update District',
            'divisions' => Division::pluck('name_mm', 'id')->toArray(),
        ]);
    }

    public function update(UpdateRequest $request, District $district): RedirectResponse
    {
        $this->authorize('has-permission', 'district-edit');
        return $this->handleServiceCall(
            fn() => $this->service->update($district, $request->validated()),
            'districts.index'
        );
    }

    public function destroy(District $district): RedirectResponse
    {
        $this->authorize('has-permission', 'district-delete');
        return $this->handleServiceCall(
            fn() => $this->service->delete($district),
            'districts.index'
        );
    }

    public function filtered(Request $request, int $division_id): JsonResponse
    {
        return response()->json(
            $this->service->filtered($request, $division_id)
        );
    }

    public function export(
        Request $request,
        DistrictFilter $filter
    ) {
        $this->authorize('has-permission', 'district-export');
        $query = $filter->apply($this->service?->getData(['division']));
        $filename = 'ခရိုင်များ_' . time() . '.xlsx';
        return Excel::download(new DistrictExport($query), $filename);
    }
}
