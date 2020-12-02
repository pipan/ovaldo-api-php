<?php

namespace App\Http\Controllers;

use App\Http\Bearer\Bearer;
use App\Http\ResponseError;
use App\Http\Schema;
use App\Repository\RoomRepository;
use App\Room\RoomTimeFactory;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;

class RoomController extends Controller
{
    private $roomRepository;
    private $roomTimeFactory;
    private $bearer;

    public function __construct(RoomRepository $roomRepository, Bearer $bearer, RoomTimeFactory $roomTimeFactory)
    {
        $this->roomRepository = $roomRepository;
        $this->bearer = $bearer;
        $this->roomTimeFactory = $roomTimeFactory;
    }

    public function create(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'name' => ['bail', 'required', 'max:50']
        ]);
        if ($validator->fails()) {
            return ResponseError::invalidRequest($validator->errors());
        }

        $roomName = $request->input('name');
        $room = $this->roomRepository->findByName($roomName);
        if (!$room) {
            $room = $this->roomRepository->insert([
                'name' => $roomName
            ]);
        }

        return response([
            'room' => Schema::forRoom()->adapt($room->toArray()),
            'user' => Schema::forUser()->adapt($this->bearer->getUser()->toArray())
        ]);
    }

    public function view($id, Request $request)
    {
        $day = $request->input('date');
        if ($day === null) {
            $room = $this->roomRepository->find($id);
        } else {
            $dayParts = explode("-", $day);
            $roomTime = $this->roomTimeFactory->fromClientDay($dayParts[0], $dayParts[1], $dayParts[2]);
            $room = $this->roomRepository->findInDateRange($id, $roomTime->getStart(), $roomTime->getEnd());
        }
        
        if (!$room) {
            return ResponseError::resourceNotFound();
        }

        return response([
            'room' => Schema::forRoom()->adapt($room->toArray()),
            'user' => Schema::forUser()->adapt($this->bearer->getUser()->toArray())
        ]);
    }
}