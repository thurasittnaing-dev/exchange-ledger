<?php

namespace App\Repositories;

use App\Enums\Roles;
use App\Interfaces\UserRepositoryInterface;
use App\Models\Management\PermissionUser;
use App\Models\Management\User;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Storage;

class UserRepository  implements UserRepositoryInterface
{
    public function getData($relations = []): Builder
    {
        return User::query()->with($relations)->latest();
    }

    public function createUser(array $data): User
    {
        $data['password'] = Hash::make($data['password']);
        $data['role'] = Roles::ADMIN;
        $user = User::create($data);
        if (isset($data['profile_image'])) $user->uploadProfileImage($data['profile_image']);

        return $user;
    }

    public function updateUser(User $user, array $data): User
    {
        if (isset($data['profile_image'])) {
            $user->uploadProfileImage($data['profile_image']);
            unset($data['profile_image']);
        }

        $user->update($data);
        return $user;
    }

    public function updateUserPassword(array $data): User
    {
        $user = auth()->user();
        $user->update([
            'password' => Hash::make($data['password']),
        ]);
        return $user;
    }

    public function statusToggle(User $user): User
    {
        $user->is_active = !$user->is_active;
        $user->save();
        return $user;
    }

    public function deleteUser(User $user): void
    {
        if (!is_null($user->profileDocument)) {
            if (Storage::disk($user->profileDocument?->disk)->exists($user->profileDocument?->path)) {
                Storage::disk($user->profileDocument?->disk)->delete($user->profileDocument?->path);
            }
            $user->profileDocument->delete();
        }
        $user->delete();
    }

    public function updateUserPermission(User $user, array $data): User
    {
        PermissionUser::where('user_id', $user->id)->delete();
        foreach ($data['permission_ids'] as $key => $permissionId) {
            PermissionUser::create(['user_id' => $user->id, 'permission_id' => $permissionId]);
        }
        return $user;
    }
}
