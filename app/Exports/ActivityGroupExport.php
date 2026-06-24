<?php

namespace App\Exports;

use App\Enums\Status;
use Illuminate\Contracts\Database\Eloquent\Builder;
use Maatwebsite\Excel\Concerns\FromQuery;
use Maatwebsite\Excel\Concerns\WithHeadings;
use Maatwebsite\Excel\Concerns\WithMapping;

class ActivityGroupExport implements FromQuery, WithHeadings, WithMapping
{
    private int $index = 0;

    public function __construct(
        private readonly Builder $query
    ) {}

    public function query()
    {
        return $this->query->select([
            'id',
            'name',
            'is_active'
        ]);
    }

    public function headings(): array
    {
        return [
            '#',
            'Name',
            'Status'
        ];
    }

    public function map($department_types): array
    {
        return [
            ++$this->index,
            $department_types->name,
            Status::tryFrom($department_types->is_active ? 'active' : 'inactive')?->label(),
        ];
    }
}
