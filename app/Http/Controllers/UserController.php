<?php

namespace App\Http\Controllers;

use App\DataTables\UserDataTable;
use App\Enums\Positions;
use App\Enums\Roles;
use App\Enums\Status;
use App\Exports\UserExport;
use App\Filters\UserFilter;
use App\Http\Requests\User\StoreRequest;
use App\Http\Requests\User\UpdatePasswordRequest;
use App\Http\Requests\User\UpdatePermissionRequest;
use App\Http\Requests\User\UpdateRequest;
use App\Models\Master\District;
use App\Models\Master\Division;
use App\Models\Management\User;
use App\Services\UserService;
use Illuminate\Support\Facades\Auth;
use Illuminate\Foundation\Auth\Access\AuthorizesRequests;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\View\View;
use Maatwebsite\Excel\Facades\Excel;
use Symfony\Component\HttpFoundation\StreamedResponse;

class UserController extends Controller
{
    use AuthorizesRequests;

    private const BASE_PATH = 'admin.users.';

    public function __construct(
        private readonly UserService $service
    ) {}

    public function index(): View
    {
        $this->authorize('has-permission', 'user-list');

        return view(self::BASE_PATH . 'index', [
            'roles' => Roles::options(),
            'status' => Status::options(),
            'divisions' => Division::pluck('name_mm', 'id')->toArray(),
            'districts' => District::pluck('name_mm', 'id')->toArray(),
        ]);
    }

    public function data(UserDataTable $dataTable): JsonResponse
    {
        return $dataTable->ajax();
    }

    public function create(): View
    {
        $this->authorize('has-permission', 'user-create');
        return view(self::BASE_PATH . 'form', [
            'route' => route('users.store'),
            'method' => 'POST',
            'user' => null,
            'btnLabel' => 'Create User',
            'roles' => Roles::options(),
            'positions' => Positions::options(),
            'divisions' => Division::pluck('name_mm', 'id')->toArray(),
            'districts' => [],
        ]);
    }

    public function store(StoreRequest $request): RedirectResponse
    {
        $this->authorize('has-permission', 'user-create');
        return $this->handleServiceCall(
            fn() => $this->service->store($request->validated()),
            'users.index'
        );
    }

    public function edit(Request $request, User $user): View
    {
        $this->authorize('has-permission', 'user-edit');
        return view(self::BASE_PATH . 'form', [
            'route' => route('users.update', $user),
            'method' => 'PUT',
            'user' => $user,
            'btnLabel' => 'Update User',
            'roles' => Roles::options(),
            'positions' => Positions::options(),
            'divisions' => Division::pluck('name_mm', 'id')->toArray(),
            'districts' => District::query()
                ->where('division_id', $user->division_id)
                ->pluck('name_mm', 'id')
                ->toArray(),
        ]);
    }

    public function update(UpdateRequest $request, User $user): RedirectResponse
    {
        $this->authorize('has-permission', 'user-edit');
        return $this->handleServiceCall(
            fn() => $this->service->update($user, $request->validated()),
            'users.index'
        );
    }

    public function destroy(User $user): RedirectResponse
    {
        $this->authorize('has-permission', 'user-delete');
        return $this->handleServiceCall(
            fn() => $this->service->delete($user),
            'users.index'
        );
    }

    public function passwordChange(): View
    {
        return view(self::BASE_PATH . 'change-password');
    }

    public function passwordUpdate(UpdatePasswordRequest $request): RedirectResponse
    {
        return $this->handleServiceCall(
            fn() => $this->service->updatePassword($request->validated()),
            'users.index'
        );
    }

    public function permissionUpdate(UpdatePermissionRequest $request, User $user): RedirectResponse
    {
        return $this->handleServiceCall(
            fn() => $this->service->updatePermission($user, $request->validated()),
            'users.index'
        );
    }

    public function statusToggle(Request $request, User $user): RedirectResponse
    {
        return $this->handleServiceCall(
            fn() => $this->service->statusToggle($user),
            'users.index'
        );
    }

    public function impersonate(Request $request): RedirectResponse
    {
        return $this->handleServiceCall(
            fn() => $this->service->impersonate($request),
            'dashboard'
        );
    }

    public function showProfile(User $user): StreamedResponse
    {
        try {
            $userProfileDocument = $user->profileDocument;

            if (!$userProfileDocument) {
                abort(404, 'Profile not found.');
            }
            return $userProfileDocument->showFile();
        } catch (\Throwable $th) {
            abort(404, 'Profile not found.');
        }
    }

    public function setting(): View
    {
        try {
            $user = Auth::user();
            return view(self::BASE_PATH . 'settings.index', compact('user'));
        } catch (\Throwable $th) {
            abort(404, 'Profile not found.');
        }
    }

    public function twoFactor(): View
    {
        try {
            return view(self::BASE_PATH . 'settings.index');
        } catch (\Throwable $th) {
            abort(404, 'Profile not found.');
        }
    }

    public function export(
        Request $request,
        UserFilter $filter
    ) {
        $this->authorize('has-permission', 'user-export');
        $query = $filter->apply($this->service?->getData(['division', 'district']));
        $filename = 'အသုံးပြုသူများ_' . time() . '.xlsx';
        return Excel::download(new UserExport($query), $filename);
    }
}
