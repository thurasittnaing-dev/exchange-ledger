<?php

namespace App\Exports;

use Illuminate\Contracts\Database\Eloquent\Builder;
use Maatwebsite\Excel\Concerns\FromQuery;
use Maatwebsite\Excel\Concerns\WithHeadings;
use Maatwebsite\Excel\Concerns\WithMapping;

class DistrictExport implements FromQuery, WithHeadings, WithMapping
{
    private int $index = 0;

    public function __construct(
        private readonly Builder $query
    ) {}

    public function query()
    {
        return $this->query->select([
            'id',
            'name_en',
            'name_mm',
            'division_id',
            'code',
        ]);
    }

    public function headings(): array
    {
        return [
            '#',
            'Name Eng',
            'Name MM',
            'Division',
            'Code',

        ];
    }

    public function map($district): array
    {
        return [
            ++$this->index,
            $district->name_en,
            $district->name_mm,
            $district->division?->name_mm,
            $district->code,
        ];
    }
}
