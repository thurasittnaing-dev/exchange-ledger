<?php

namespace App\Repositories;

use App\Interfaces\SubstanceRepositoryInterface;
use App\Models\Master\Substance;
use Illuminate\Database\Eloquent\Builder;

class SubstanceRepository implements SubstanceRepositoryInterface
{
    public function getData(): Builder
    {
        return Substance::query();
    }

    public function createSubstance(array $data): Substance
    {
        return Substance::create($data);
    }

    public function updateSubstance(Substance $substance, array $data): Substance
    {
        $substance->update($data);

        return $substance;
    }

    public function deleteSubstance(Substance $substance): void
    {
        $substance->delete();
    }

    public function createSubSubstance(array $data, Substance $substance): Substance
    {
        $data['parent_id'] = $substance->id;

        return Substance::create($data);
    }
}
