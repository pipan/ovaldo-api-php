<?php

namespace App\Http\Controllers;

use App\Http\Bearer\Bearer;
use App\Http\ResponseError;
use App\Http\Schema;
use App\Integration\Zomato\ZomatoApi;
use App\Repository\PlaceRepository;
use App\Repository\RoomRepository;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;

class PlaceSearchController extends Controller
{
    private $placeRepository;
    private $roomRepository;
    private $zomato;
    private $bearer;

    public function __construct(PlaceRepository $placeRepository, RoomRepository $roomRepository, Bearer $bearer, ZomatoApi $zomato)
    {
        $this->placeRepository = $placeRepository;
        $this->roomRepository = $roomRepository;
        $this->bearer = $bearer;
        $this->zomato = $zomato;
    }

    public function __invoke(Request $request)
    {
        $room = $this->roomRepository->find($request->input('room_id'));
        if (!$room) {
            return ResponseError::resourceNotFound();
        }

        $validator = Validator::make($request->all(), [
            'search' => ['bail', 'required']
        ]);
        if ($validator->fails()) {
            return ResponseError::invalidRequest($validator->errors());
        }

        $search = $request->input('search');
        $places = [];
        $zomatoPlaces = $this->zomato->search($search, $request->input('lon', env('GEO_LON')), $request->input('lat', env('GEO_LAT')));
        if (isset($zomatoPlaces['restaurants'])) {
            foreach ($zomatoPlaces['restaurants'] as $place) {
                $places[] = [
                    'id' => null,
                    'external_id' => $place['restaurant']['R']['res_id'],
                    'name' => $place['restaurant']['name'],
                    'location' => $place['restaurant']['location']['locality_verbose']
                ];
            }
        }
        

        $placeEntities = $this->placeRepository->searchForRoom($room->id, $search);
        foreach ($placeEntities as $place) {
            $places[] = $place->toArray();
        }
        
        return response([
            'places' => Schema::forPlaces()->adapt($places),
            'user' => Schema::forUser()->adapt($this->bearer->getUser()->toArray())
        ]);
    }
}