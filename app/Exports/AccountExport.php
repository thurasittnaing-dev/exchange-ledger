<?php

namespace App\Exports;

use App\Enums\Provider;
use Illuminate\Contracts\Database\Eloquent\Builder;
use Maatwebsite\Excel\Concerns\FromQuery;
use Maatwebsite\Excel\Concerns\WithHeadings;
use Maatwebsite\Excel\Concerns\WithMapping;

class AccountExport implements FromQuery, WithHeadings, WithMapping
{
    private int $index = 0;

    public function __construct(
        private readonly Builder $query
    ) {}

    public function query()
    {
        return $this->query->with('bankType');
    }

    public function headings(): array
    {
        return [
            '#',
            'Name',
            'Bank Type',
            'Provider',
            'Balance',
        ];
    }

    public function map($account): array
    {
        $provider = $account->bankType?->provider;

        return [
            ++$this->index,
            $account->name,
            $account->bankType?->name ?? '-',
            $provider ? (Provider::tryFrom($provider)?->label() ?? $provider) : '-',
            $account->balance,
        ];
    }
}
