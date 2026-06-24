<?php

namespace App\Exports;

use Illuminate\Contracts\Database\Eloquent\Builder;
use Maatwebsite\Excel\Concerns\FromQuery;
use Maatwebsite\Excel\Concerns\WithHeadings;
use Maatwebsite\Excel\Concerns\WithMapping;

class AccountBalanceHistoryExport implements FromQuery, WithHeadings, WithMapping
{
    private int $index = 0;

    public function __construct(
        private readonly Builder $query
    ) {}

    public function query()
    {
        return $this->query->with('account');
    }

    public function headings(): array
    {
        return [
            '#',
            'Date',
            'Account',
            'Type',
            'Action',
            'Amount',
            'Running Balance',
            'Description',
        ];
    }

    public function map($history): array
    {
        return [
            ++$this->index,
            dateFormat($history->created_at, true),
            $history->account?->name ?? '-',
            $history->reference_type?->label() ?? '-',
            $history->action?->label() ?? '-',
            $history->amount,
            $history->running_balance,
            $history->description,
        ];
    }
}
