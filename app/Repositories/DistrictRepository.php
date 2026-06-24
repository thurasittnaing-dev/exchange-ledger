<?php

namespace App\Repositories;

use App\Interfaces\DistrictRepositoryInterface;
use App\Models\Master\District;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Http\Request;
use Illuminate\Support\Collection;

class DistrictRepository  implements DistrictRepositoryInterface
{
    public function getData($relations = []): Builder
    {
        return District::query()->with($relations);
    }

    public function createDistrict(array $data): District
    {
        return District::create($data);
    }

    public function updateDistrict(District $district, array $data): District
    {
        $district->update($data);
        return $district;
    }

    public function deleteDistrict(District $district): void
    {
        $district->delete();
    }

    public function filtered(Request $request, int $division_id): Collection
    {
        return District::query()
            ->where('division_id', $division_id)
            ->select('id', 'name_mm')
            ->orderBy('name_mm')
            ->get();
    }
}
