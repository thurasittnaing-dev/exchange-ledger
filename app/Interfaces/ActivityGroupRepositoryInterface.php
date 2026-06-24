<?php

namespace App\Interfaces;

use App\Models\Master\ActivityGroup;
use Illuminate\Database\Eloquent\Builder;

interface ActivityGroupRepositoryInterface
{
    public function getData(): Builder;
    public function createActivityGroup(array $data): ActivityGroup;
    public function updateActivityGroup(ActivityGroup $activityGroup, array $data): ActivityGroup;
    public function deleteActivityGroup(ActivityGroup $activityGroup): void;

    public function createSubActivityGroup(array $data, ActivityGroup $activityGroup): ActivityGroup;
}
