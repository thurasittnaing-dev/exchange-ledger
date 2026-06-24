<?php

namespace App\Exports;

use Illuminate\Contracts\Database\Eloquent\Builder;
use Maatwebsite\Excel\Concerns\FromQuery;
use Maatwebsite\Excel\Concerns\WithHeadings;
use Maatwebsite\Excel\Concerns\WithMapping;

class CashMoneyExport implements FromQuery, WithHeadings, WithMapping
{
    private int $index = 0;

    public function __construct(
        private readonly Builder $query
    ) {}

    public function query()
    {
        return $this->query;
    }

    public function headings(): array
    {
        return [
            '#',
            'Date',
            'Type',
            'Action',
            'Amount',
            'Previous Balance',
            'Description',
        ];
    }

    public function map($history): array
    {
        return [
            ++$this->index,
            dateFormat($history->created_at, true),
            $history->reference_type?->label() ?? '-',
            $history->action?->label() ?? '-',
            $history->amount,
            $history->running_balance,
            $history->description,
        ];
    }
}
