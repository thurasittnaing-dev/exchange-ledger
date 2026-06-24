<?php

namespace App\Exports;

use App\Enums\Roles;
use App\Enums\Status;
use Illuminate\Contracts\Database\Eloquent\Builder;
use Maatwebsite\Excel\Concerns\FromQuery;
use Maatwebsite\Excel\Concerns\WithHeadings;
use Maatwebsite\Excel\Concerns\WithMapping;

class UserExport implements FromQuery, WithHeadings, WithMapping
{
    private int $index = 0;

    public function __construct(
        private readonly Builder $query
    ) {}

    public function query()
    {
        return $this->query->select([
            'id',
            'full_name',
            'username',
            'email',
            'role',
            'division_id',
            'district_id',
            'position',
            'is_temporary_officer',
            'is_active',
            'created_at',
            'last_login_at',
        ]);
    }

    public function headings(): array
    {
        return [
            '#',
            'Name',
            'Username',
            'Email',
            'Role',
            'Division',
            'District',
            'Position',
            'Temporary Officer',
            'Status',
            'Created At',
            'Last Login',
        ];
    }

    public function map($user): array
    {
        return [
            ++$this->index,
            $user->full_name,
            $user->username,
            $user->email,
            Roles::tryFrom($user->role)?->label(),
            $user->division?->name_mm,
            $user->district?->name_mm,
            $user->position?->label() ?? '-',
            $user->is_temporary_officer ? 'Yes' : 'No',
            Status::tryFrom($user->is_active ? 'active' : 'inactive')?->label(),
            dateFormat($user->created_at, true),
            dateFormat($user->last_login_at, true),
        ];
    }
}
