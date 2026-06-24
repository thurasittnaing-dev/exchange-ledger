<?php

namespace App\Exports;

use App\Enums\Provider;
use Illuminate\Contracts\Database\Eloquent\Builder;
use Maatwebsite\Excel\Concerns\FromQuery;
use Maatwebsite\Excel\Concerns\WithHeadings;
use Maatwebsite\Excel\Concerns\WithMapping;

class BankTypeExport implements FromQuery, WithHeadings, WithMapping
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
            'provider',
        ]);
    }

    public function headings(): array
    {
        return [
            '#',
            'Name',
            'Provider',
        ];
    }

    public function map($bankType): array
    {
        return [
            ++$this->index,
            $bankType->name,
            $bankType->provider instanceof Provider
                ? $bankType->provider->label()
                : (Provider::tryFrom($bankType->provider)?->label() ?? $bankType->provider),
        ];
    }
}
