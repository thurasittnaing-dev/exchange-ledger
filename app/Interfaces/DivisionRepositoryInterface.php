<?php

namespace App\Interfaces;

use App\Models\Master\Division;
use Illuminate\Database\Eloquent\Builder;

interface DivisionRepositoryInterface
{
    public function getData(): Builder;

    public function createDivision(array $data): Division;

    public function updateDivision(Division $division, array $data): Division;

    public function deleteDivision(Division $division): void;
}
