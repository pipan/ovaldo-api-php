<?php

namespace App\Repository;

use App\Room\RoomTimeFactory;
use Carbon\CarbonImmutable;

class RoomRepository
{
    private $roomTimeFactory;

    public function __construct(RoomTimeFactory $roomTimeFactory)
    {
        $this->roomTimeFactory = $roomTimeFactory;
    }

    public function findByName($name)
    {
        $roomTime = $this->roomTimeFactory->fromNow();;
        return RoomEntity::with([
                'activities' => function($query) use ($roomTime) {
                    $query->where('starts_at', '>=', $roomTime->getStart()->format('Y-m-d H:i:s'));
                },
                'activities.place',
                'activities.created_by',
                'activities.users'
            ])
            ->where('name', $name)
            ->first();
    }

    public function find($id)
    {
        $roomTime = $this->roomTimeFactory->fromNow();
        return $this->findInDateRange($id, $roomTime->getStart(), $roomTime->getEnd());
    }

    public function findInDateRange($id, CarbonImmutable $dateSince, CarbonImmutable $dateTo)
    {
        return RoomEntity::with([
                'activities' => function($query) use ($dateSince, $dateTo) {
                    $query->where('starts_at', '>=', $dateSince->format("Y-m-d H:i:s"))
                        ->where('starts_at', '<', $dateTo->format("Y-m-d H:i:s"));
                },
                'activities.place',
                'activities.created_by',
                'activities.users'
            ])->find($id);
    }

    public function insert($room)
    {
        $entity = new RoomEntity($room);
        $entity->save();
        return $entity;
    }
}