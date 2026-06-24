<?php

namespace App\Repositories;

use App\Interfaces\ActivityGroupRepositoryInterface;
use App\Models\Master\ActivityGroup;
use Illuminate\Database\Eloquent\Builder;

class ActivityGroupRepository implements ActivityGroupRepositoryInterface
{
    public function getData(): Builder
    {
        return ActivityGroup::query();
    }
    public function createActivityGroup(array $data): ActivityGroup
    {
        $data['is_active'] = isset($data['is_active']) ? true : false;
        return ActivityGroup::create($data);
    }
    public function updateActivityGroup(ActivityGroup $activityGroup, array $data): ActivityGroup
    {
        $data['is_active'] = isset($data['is_active']) ? true : false;
        $activityGroup->update($data);
        return $activityGroup;
    }
    public function deleteActivityGroup(ActivityGroup $activityGroup): void
    {
        $activityGroup->delete();
    }

    public function createSubActivityGroup(array $data, ActivityGroup $activityGroup): ActivityGroup
    {
        $data['parent_id'] = $activityGroup->id;
        $data['is_active'] = isset($data['is_active']) ? true : false;

        return ActivityGroup::create($data);
    }
}
