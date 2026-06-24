<?php

namespace App\Exports;

use Illuminate\Contracts\Database\Eloquent\Builder;
use Maatwebsite\Excel\Concerns\FromQuery;
use Maatwebsite\Excel\Concerns\WithHeadings;
use Maatwebsite\Excel\Concerns\WithMapping;

class PermissionExport implements FromQuery, WithHeadings, WithMapping
{
    private int $index = 0;

    public function __construct(
        private readonly Builder $query
    ) {}

    public function query()
    {
        return $this->query->select([
            'id',
            'module',
            'name',
            'code',
            'created_at',
        ]);
    }

    public function headings(): array
    {
        return [
            '#',
            'Module',
            'Name',
            'Code',
            'Created At',
        ];
    }

    public function map($user): array
    {
        return [
            ++$this->index,
            $user->module,
            $user->name,
            $user->code,
            dateFormat($user->created_at, true),
        ];
    }
}
