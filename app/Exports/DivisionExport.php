<?php

namespace App\Exports;

use Illuminate\Contracts\Database\Eloquent\Builder;
use Maatwebsite\Excel\Concerns\FromQuery;
use Maatwebsite\Excel\Concerns\WithHeadings;
use Maatwebsite\Excel\Concerns\WithMapping;

class DivisionExport implements FromQuery, WithHeadings, WithMapping
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
            'code',
            'level',
        ]);
    }

    public function headings(): array
    {
        return [
            '#',
            'Name_Eng',
            'Name_MM',
            'Code',
            'Level',
        ];
    }

    public function map($division): array
    {
        return [
            ++$this->index,
            $division->name_en,
            $division->name_mm,
            $division->code,
            $division->level,

        ];
    }
}
