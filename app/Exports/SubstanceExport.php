<?php

namespace App\Exports;

use App\Enums\Status;
use Illuminate\Contracts\Database\Eloquent\Builder;
use Maatwebsite\Excel\Concerns\FromQuery;
use Maatwebsite\Excel\Concerns\WithHeadings;
use Maatwebsite\Excel\Concerns\WithMapping;

class SubstanceExport implements FromQuery, WithHeadings, WithMapping
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
            'is_active',
        ]);
    }

    public function headings(): array
    {
        return [
            '#',
            'Name',
            'Status',
        ];
    }

    public function map($substance): array
    {
        return [
            ++$this->index,
            $substance->name,
            Status::tryFrom($substance->is_active ? 'active' : 'inactive')?->label(),
        ];
    }
}
