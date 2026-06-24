<?php

declare(strict_types=1);

namespace App\Services;

use App\Interfaces\UserRepositoryInterface;
use App\Models\Management\User;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;

final class UserService
{
    public function __construct(
        private readonly UserRepositoryInterface $repository
    ) {}

    public function getData($relations = []): Builder
    {
        return $this->repository->getData($relations);
    }

    public function impersonate($request): array
    {
        try {
            if ($request->account_id) {
                session(['impersonator' => auth()->id()]);
                Auth::loginUsingId($request->account_id);
            }

            return [
                'status' => true,
                'message' => 'Login successfully.'
            ];
        } catch (\Exception $e) {
            showError($e, "Impersonate Error: ");
            return [
                'status' => false,
                'message' => 'Something went wrong during impersonate.'
            ];
        }
    }

    public function store(array $data): array
    {
        try {
            DB::beginTransaction();
            $this->repository->createUser($data);

            DB::commit();
            return [
                'status' => true,
                'message' => 'User created successfully.'
            ];
        } catch (\Exception $e) {
            DB::rollBack();
            showError($e, "User Create Error: ");
            return [
                'status' => false,
                'message' => 'Something went wrong during creation.'
            ];
        }
    }

    public function update(User $user, array $data): array
    {
        try {
            DB::beginTransaction();
            $user = $this->repository->updateUser($user, $data);

            DB::commit();
            return [
                'status' => true,
                'message' => 'User updated successfully.'
            ];
        } catch (\Exception $e) {
            DB::rollBack();
            showError($e, "User Update Error: ");
            return [
                'status' => false,
                'message' => 'Something went wrong during creation.'
            ];
        }
    }

    public function updatePassword(array $data): array
    {
        try {
            $this->repository->updateUserPassword($data);
            return [
                'status' => true,
                'message' => 'Password updated successfully.'
            ];
        } catch (\Exception $e) {
            showError($e, "User Password Update Error: ");
            return [
                'status' => false,
                'message' => 'Something went wrong during creation.'
            ];
        }
    }

    public function updatePermission(User $user, array $data): array
    {
        try {
            $this->repository->updateUserPermission($user, $data);
            return [
                'status' => true,
                'message' => 'Permission updated successfully.'
            ];
        } catch (\Exception $e) {
            showError($e, "Permission Update Error: ");
            return [
                'status' => false,
                'message' => 'Something went wrong during creation.'
            ];
        }
    }

    public function statusToggle(User $user): array
    {
        try {
            $this->repository->statusToggle($user);
            return [
                'status' => true,
                'message' => 'Status toggle success.'
            ];
        } catch (\Exception $e) {
            showError($e, "Status toggle Error: ");
            return [
                'status' => false,
                'message' => 'Something went wrong during creation.'
            ];
        }
    }

    public function delete(User $user): array
    {
        try {
            DB::beginTransaction();
            $this->repository->deleteUser($user);

            DB::commit();
            return [
                'status' => true,
                'message' => 'User deleted successfully.'
            ];
        } catch (\Exception $e) {
            DB::rollBack();
            showError($e, "User Delete Error: ");
            return [
                'status' => false,
                'message' => 'Something went wrong during creation.'
            ];
        }
    }
}
