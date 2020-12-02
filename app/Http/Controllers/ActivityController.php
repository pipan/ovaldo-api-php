<?php

namespace App\Http\Controllers;

use App\Http\Bearer\Bearer;
use App\Http\ResponseError;
use App\Http\Schema;
use App\Integration\Zomato\ZomatoApi;
use App\Repository\ActivityRepository;
use App\Repository\PlaceRepository;
use App\Repository\RoomRepository;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;

class ActivityController extends Controller
{
    private $roomRepository;
    private $activityRepository;
    private $placeRepository;
    private $bearer;
    private $zomato;

    public function __construct(RoomRepository $roomRepository, ActivityRepository $activityRepository, PlaceRepository $placeRepository, Bearer $bearer, ZomatoApi $zomato)
    {
        $this->roomRepository = $roomRepository;
        $this->activityRepository = $activityRepository;
        $this->placeRepository = $placeRepository;
        $this->bearer = $bearer;
        $this->zomato = $zomato;
    }

    public function create($id, Request $request)
    {
        $room = $this->roomRepository->find($id);
        if (!$room) {
            return ResponseError::resourceNotFound();
        }
        $validator = Validator::make($request->all(), [
            'starts_at' => ['bail', 'required', 'date'],
            'place.name' => ['bail', 'required', 'max:50'],
        ]);
        if ($validator->fails()) {
            return ResponseError::invalidRequest($validator->errors());
        }

        $place = null;
        $placeExternalId = $request->input('place.external_id');
        $placeId = $request->input('place.id');
        if ($placeExternalId !== null) {
            $place = $this->placeRepository->findByExternalId($placeExternalId);
        } elseif ($placeId !== null) {
            $place = $this->placeRepository->find($placeId);
        }

        if (!$place) {
            $place = $this->placeRepository->insert([
                'name' => $request->input('place.name'),
                'external_id' => $request->input('place.external_id')
            ]);
        }

        if ($place->external_id) {
            $menu = $this->zomato->dailymenu($place->external_id);
            $place = $this->placeRepository->update($place->id, [
                'menu' => $menu['daily_menus'] ?? []
            ]);
        }
        
        $text = $request->input('text', '');
        if ($text === null) {
            $text = '';
        }
        $activity = $this->activityRepository->insert([
            'room_id' => $id,
            'place_id' => $place->id,
            'created_by' => $this->bearer->getUser()->id,
            'text' => $text,
            'starts_at' => $request->input('starts_at')
        ]);
        $activity = $this->activityRepository->find($activity->id);

        return response([
            'activity' => Schema::forActivity()->adapt($activity->toArray()),
            'user' => Schema::forUser()->adapt($this->bearer->getUser()->toArray())
        ]);
    }
}