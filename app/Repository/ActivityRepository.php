<?php

namespace App\Repository;

use App\Room\RoomTimeFactory;
use Illuminate\Support\Facades\DB;

class ActivityRepository
{
    private $roomTimeFactory;

    public function __construct(RoomTimeFactory $roomTimeFactory)
    {
        $this->roomTimeFactory = $roomTimeFactory;
    }

    public function find($id)
    {
        return ActivityEntity::with(['place', 'created_by', 'users'])
            ->find($id);
    }

    public function insert($activity)
    {
        $activity = new ActivityEntity($activity);
        $activity->save();
        return $activity;
    }

    public function join($activity, $userId)
    {
        $roomTime = $this->roomTimeFactory->fromNow();
        $start = $roomTime->getStart()->format("Y-m-d H:i:s");
        DB::table('activity_user')
            ->join('activity', 'activity_user.activity_id', '=', 'activity_id')
            ->where('activity.starts_at', '>=', $start)
            ->where('activity_user.user_id', '=', $userId)
            ->delete();
        $entity = new ActivityUserEntity([
            'activity_id' => $activity->id,
            'user_id' => $userId
        ]);
        $entity->save();
    }

    public function leave($activity, $userId)
    {
        ActivityUserEntity::where('activity_id', '=', $activity->id)
            ->where('user_id', '=', $userId)
            ->delete();
    }
}