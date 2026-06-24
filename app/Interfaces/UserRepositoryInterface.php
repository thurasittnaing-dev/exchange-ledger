<?php

namespace App\Interfaces;

use App\Models\Management\User;
use Illuminate\Database\Eloquent\Builder;

interface UserRepositoryInterface
{
    public function getData($relations = []): Builder;

    public function createUser(array $data): User;

    public function updateUser(User $user, array $data): User;

    public function updateUserPassword(array $data): User;

    public function updateUserPermission(User $user, array $data): User;

    public function statusToggle(User $user): User;

    public function deleteUser(User $user): void;
}
