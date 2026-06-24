<?php

declare(strict_types=1);

namespace App\Services;

use App\Interfaces\ActivityGroupRepositoryInterface;
use App\Models\Master\ActivityGroup;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;

final class ActivityGroupService
{
    public function __construct(
        private readonly ActivityGroupRepositoryInterface $repository
    ) {}

    public function getData(): Builder
    {
        return $this->repository->getData();
    }

    public function store(array $data): array
    {
        try {
            DB::beginTransaction();
            $this->repository->createActivityGroup($data);

            DB::commit();
            return [
                'status' => true,
                'message' => 'Activity Group created successfully.'
            ];
        } catch (\Exception $e) {
            DB::rollBack();
            showError($e, "Activity Group Create Error: ");
            return [
                'status' => false,
                'message' => 'Something went wrong during creation.'
            ];
        }
    }

    public function update(ActivityGroup $activityGroup, array $data): array
    {
        try {
            DB::beginTransaction();
            $this->repository->updateActivityGroup($activityGroup, $data);

            DB::commit();
            return [
                'status' => true,
                'message' => 'Activity Group updated successfully.'
            ];
        } catch (\Exception $e) {
            DB::rollBack();
            showError($e, "Activity Group Update Error: ");
            return [
                'status' => false,
                'message' => 'Something went wrong during update.'
            ];
        }
    }

    public function delete(ActivityGroup $activityGroup): array
    {
        try {
            DB::beginTransaction();
            $this->repository->deleteActivityGroup($activityGroup);

            DB::commit();
            return [
                'status' => true,
                'message' => 'Activity Group deleted successfully.'
            ];
        } catch (\Exception $e) {
            DB::rollBack();
            showError($e, "Activity Group Delete Error: ");
            return [
                'status' => false,
                'message' => 'Something went wrong during deletion.'
            ];
        }
    }

    public function storeSubActivityGroup(array $data, ActivityGroup $activityGroup): array
    {
        try {
            $this->repository->createSubActivityGroup($data, $activityGroup);

            return [
                'status' => true,
                'message' => 'Sub Activity Group created successfully.'
            ];
        } catch (\Exception $e) {
            showError($e, "Sub Activity Group Create Error: ");
            return [
                'status' => false,
                'message' => 'Something went wrong during sub activity group creation.'
            ];
        }
    }
}
