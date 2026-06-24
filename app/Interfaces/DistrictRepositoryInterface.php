<?php

namespace App\Interfaces;

use App\Models\Master\District;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Http\Request;
use Illuminate\Support\Collection;

interface DistrictRepositoryInterface
{
    public function getData(): Builder;

    public function createDistrict(array $data): District;

    public function updateDistrict(District $district, array $data): District;

    public function deleteDistrict(District $district): void;

    public function filtered(Request $request, int $division_id): Collection;
}
