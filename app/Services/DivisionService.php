<?php

declare(strict_types=1);

namespace App\Services;

use App\Interfaces\DivisionRepositoryInterface;
use App\Models\Master\Division;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Support\Facades\Log;


final class DivisionService
{
    public function __construct(
        private readonly DivisionRepositoryInterface $repository
    ) {}

    public function getData(): Builder
    {
        return $this->repository->getData();
    }

    public function store(array $data): array
    {
        try {
            $this->repository->createDivision($data);
            return [
                'status' => true,
                'message' => 'Division created successfully'
            ];
        } catch (\Exception $e) {
            showError($e, "Division Create Error: ");
            return [
                'status' => false,
                'message' => 'Something went wrong during Division creation'
            ];
        }
    }

    public function update(Division $division, array $data): array
    {
        try {
            $division = $this->repository->updateDivision($division, $data);
            return [
                'status' => true,
                'message' => 'Division updated successfully.'
            ];
        } catch (\Exception $e) {
            showError($e, "Division Update Error: ");
            return [
                'status' => false,
                'message' => 'Something went wrong during Division update.'
            ];
        }
    }

    public function delete(Division $division): array
    {
        try {
            $this->repository->deleteDivision($division);
            return [
                'status' => true,
                'message' => 'Division deleted successfully.'
            ];
        } catch (\Exception $e) {
            showError($e, "Division Delete Error: ");
            return [
                'status' => false,
                'message' => 'Something went wrong during Division delete.'
            ];
        }
    }
}
