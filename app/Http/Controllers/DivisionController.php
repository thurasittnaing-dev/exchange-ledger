<?php

namespace App\Http\Controllers;

use App\DataTables\DivisionDataTable;
use App\Exports\DivisionExport;
use App\Filters\DivisionFilter;
use App\Http\Requests\Division\StoreRequest;
use App\Http\Requests\Division\UpdateRequest;
use App\Models\Master\Division;
use App\Services\DivisionService;
use Illuminate\Foundation\Auth\Access\AuthorizesRequests;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\View\View;
use Maatwebsite\Excel\Facades\Excel;


class DivisionController extends Controller
{
    use AuthorizesRequests;

    private const BASE_PATH = 'admin.divisions.';

    public function __construct(
        private readonly DivisionService $service
    ) {}

    public function index(): View
    {
        $this->authorize('has-permission', 'division-list');
        return view(self::BASE_PATH . 'index');
    }

    public function data(DivisionDataTable $dataTable): JsonResponse
    {
        return $dataTable->ajax();
    }

    public function create(): View
    {
        $this->authorize('has-permission', 'division-create');
        return view(self::BASE_PATH . 'form', [
            'route' => route('divisions.store'),
            'method' => 'POST',
            'division' => null,
            'btnLabel' => 'Create Division',
        ]);
    }

    public function store(StoreRequest $request): RedirectResponse
    {
        $this->authorize('has-permission', 'division-create');
        return $this->handleServiceCall(
            fn() => $this->service->store($request->validated()),
            'divisions.index'
        );
    }

    public function edit(Request $request, Division $division): View
    {
        $this->authorize('has-permission', 'division-edit');
        return view(self::BASE_PATH . 'form', [
            'route' => route('divisions.update', $division),
            'method' => 'PUT',
            'division' => $division,
            'btnLabel' => 'Update Division',
        ]);
    }

    public function update(UpdateRequest $request, Division $division): RedirectResponse
    {
        $this->authorize('has-permission', 'division-edit');
        return $this->handleServiceCall(
            fn() => $this->service->update($division, $request->validated()),
            'divisions.index'
        );
    }

    public function destroy(Division $division): RedirectResponse
    {
        $this->authorize('has-permission', 'division-delete');
        return $this->handleServiceCall(
            fn() => $this->service->delete($division),
            'divisions.index'
        );
    }

    public function export(
        Request $request,
        DivisionFilter $filter
    ) {
        $this->authorize('has-permission', 'division-export');
        $query = $filter->apply($this->service?->getData());
        $filename = 'တိုင်းဒေသကြီးများ_' . time() . '.xlsx';
        return Excel::download(new DivisionExport($query), $filename);
    }
}
