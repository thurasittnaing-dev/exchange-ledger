<?php

declare(strict_types=1);

namespace App\Services;

use App\Interfaces\DistrictRepositoryInterface;
use App\Models\Master\District;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Http\Request;
use Illuminate\Support\Collection;
use Illuminate\Support\Facades\Log;

final class DistrictService
{
    public function __construct(
        private readonly DistrictRepositoryInterface $repository
    ) {}

    public function getData($relations = []): Builder
    {
        return $this->repository->getData($relations);
    }

    public function store(array $data): array
    {
        try {
            $this->repository->createDistrict($data);
            return [
                'status' => true,
                'message' => 'District created successfully.'
            ];
        } catch (\Exception $e) {
            showError($e, "District Create Error: ");
            return [
                'status' => false,
                'message' => 'Something went wrong during creation.'
            ];
        }
    }

    public function update(District $district, array $data): array
    {
        try {
            $this->repository->updateDistrict($district, $data);
            return [
                'status' => true,
                'message' => 'District updated successfully.'
            ];
        } catch (\Exception $e) {
            showError($e, "District Update Error: ");
            return [
                'status' => false,
                'message' => 'Something went wrong during creation.'
            ];
        }
    }

    public function delete(District $district): array
    {
        try {
            $this->repository->deleteDistrict($district);
            return [
                'status' => true,
                'message' => 'District deleted successfully.'
            ];
        } catch (\Exception $e) {
            showError($e, "District Delete Error: ");
            return [
                'status' => false,
                'message' => 'Something went wrong during creation.'
            ];
        }
    }

    public function filtered(Request $request, int $division_id): Collection
    {
        return $this->repository->filtered($request, $division_id);
    }
}
