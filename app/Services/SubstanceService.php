<?php

declare(strict_types=1);

namespace App\Services;

use App\Models\Master\Substance;
use App\Repositories\SubstanceRepository;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;

final class SubstanceService
{
    public function __construct(
        private readonly SubstanceRepository $repository
    ) {}

    public function getData(): Builder
    {
        return $this->repository->getData();
    }

    public function store(array $data): array
    {
        try {
            DB::beginTransaction();
            $this->repository->createSubstance($data);
            DB::commit();

            return [
                'status' => true,
                'message' => 'Substance created successfully.',
            ];
        } catch (\Exception $e) {
            DB::rollBack();
            showError($e, "Substance Create Error: ");

            return [
                'status' => false,
                'message' => 'Something went wrong during creation.',
            ];
        }
    }

    public function update(Substance $substance, array $data): array
    {
        try {
            DB::beginTransaction();
            $this->repository->updateSubstance($substance, $data);
            DB::commit();

            return [
                'status' => true,
                'message' => 'Substance updated successfully.',
            ];
        } catch (\Exception $e) {
            DB::rollBack();
            showError($e, "Substance Update Error: ");

            return [
                'status' => false,
                'message' => 'Something went wrong during update.',
            ];
        }
    }

    public function delete(Substance $substance): array
    {
        try {
            DB::beginTransaction();
            $this->repository->deleteSubstance($substance);
            DB::commit();

            return [
                'status' => true,
                'message' => 'Substance deleted successfully.',
            ];
        } catch (\Exception $e) {
            DB::rollBack();
            showError($e, "Substance Delete Error: ");

            return [
                'status' => false,
                'message' => 'Something went wrong during deletion.',
            ];
        }
    }

    public function storeSubSubstance(array $data, Substance $substance): array
    {
        try {
            $this->repository->createSubSubstance($data, $substance);

            return [
                'status' => true,
                'message' => 'Sub substance created successfully.',
            ];
        } catch (\Exception $e) {
            showError($e, "Sub Substance Create Error: ");

            return [
                'status' => false,
                'message' => 'Something went wrong during sub substance creation.',
            ];
        }
    }
}
