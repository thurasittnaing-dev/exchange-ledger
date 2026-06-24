<?php

namespace App\Repositories;

use App\Interfaces\DivisionRepositoryInterface;
use App\Models\Master\Division;
use Illuminate\Database\Eloquent\Builder;

class DivisionRepository implements DivisionRepositoryInterface
{
    public function getData(): Builder
    {
        return Division::query();
    }

    public function createDivision(array $data): Division
    {
        return Division::create($data);
    }

    public function updateDivision(Division $division, array $data): Division
    {
        $division->update($data);
        return ($division);
    }

    public function deleteDivision(Division $division): void
    {
        $division->delete();
    }
}
