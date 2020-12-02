<?php

namespace App\Repository;

class PlaceRepository
{
    public function find($id)
    {
        return PlaceEntity::find($id);
    }

    public function findByExternalId($externalId)
    {
        return PlaceEntity::where('external_id', '=', $externalId)
            ->first();
    }

    public function searchForRoom($roomId, $search)
    {
        return PlaceEntity::whereIn('id', function($query) use ($roomId) {
                $query->from('activity')
                    ->select('place_id')
                    ->join('place', 'activity.place_id', '=', 'place.id')
                    ->where('activity.room_id', '=', $roomId)
                    ->groupBy('place_id');
            })
            ->where('name', 'like', $search . '%')
            ->orderBy('name')
            ->limit(5)
            ->get();
    }

    public function insert($place)
    {
        $entity = new PlaceEntity($place);
        $entity->save();
        return $entity;
    }

    public function update($id, $placeData)
    {
        $entity = $this->find($id);
        $entity->fill($placeData);
        $entity->save();
        return $entity;
    }
}