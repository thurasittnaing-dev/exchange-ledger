<?php

namespace App\Exports;

use Illuminate\Contracts\Database\Eloquent\Builder;
use Maatwebsite\Excel\Concerns\FromQuery;
use Maatwebsite\Excel\Concerns\WithHeadings;
use Maatwebsite\Excel\Concerns\WithMapping;

class TransactionExport implements FromQuery, WithHeadings, WithMapping
{
    private int $index = 0;

    public function __construct(
        private readonly Builder $query
    ) {}

    public function query()
    {
        return $this->query->with(['account.bankType']);
    }

    public function headings(): array
    {
        return [
            '#',
            'Date',
            'Account',
            'Bank Type',
            'Type',
            'Fee Type',
            'Amount',
            'Fee',
            'Cash Impact',
            'EMoney Impact',
            'Cash Profit',
            'EMoney Profit',
            'Description',
        ];
    }

    public function map($transaction): array
    {
        return [
            ++$this->index,
            dateFormat($transaction->created_at, true),
            $transaction->account?->name ?? '-',
            $transaction->account?->bankType?->name ?? '-',
            $transaction->type?->label() ?? '-',
            $transaction->fee_type?->label() ?? '-',
            $transaction->amount,
            $transaction->fee_amount,
            $transaction->total_cash_impact,
            $transaction->total_emoney_impact,
            $transaction->fee_cash_profit,
            $transaction->fee_emoney_profit,
            $transaction->description,
        ];
    }
}
