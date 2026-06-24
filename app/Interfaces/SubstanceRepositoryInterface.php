<?php

namespace App\Interfaces;

use App\Models\Master\Substance;
use Illuminate\Database\Eloquent\Builder;

interface SubstanceRepositoryInterface
{
    public function getData(): Builder;

    public function createSubstance(array $data): Substance;

    public function updateSubstance(Substance $substance, array $data): Substance;

    public function deleteSubstance(Substance $substance): void;

    public function createSubSubstance(array $data, Substance $substance): Substance;
}
