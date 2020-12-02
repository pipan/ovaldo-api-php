<?php

namespace App\Http\Controllers;

use App\Http\Bearer\Bearer;
use App\Http\ResponseError;
use App\Http\Schema;
use App\Repository\GuestRepository;
use App\Repository\UserRepository;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Cookie;
use Illuminate\Support\Facades\Validator;

class UserController extends Controller
{
    private $userRepository;
    private $guestRepository;
    private $bearer;

    public function __construct(UserRepository $userRepository, GuestRepository $guestRepository, Bearer $bearer)
    {
        $this->userRepository = $userRepository;
        $this->guestRepository = $guestRepository;
        $this->bearer = $bearer;
    }

    public function update(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'name' => ['bail', 'required', 'max:50']
        ]);
        if ($validator->fails()) {
            return ResponseError::invalidRequest($validator->errors());
        }

        if (!$this->bearer->hasUser()) {
            $user = $this->userRepository->insert([]);
            $guest = $this->guestRepository->insert([
                'user_id' => $user->id,
                'accessed_at' => date("Y-m-d H:i:s")
            ]);
            Cookie::queue(Cookie::make('user_hash', $guest->hash, 30 * 24 * 60));
            $userId = $user->id;
        } else {
            $userId = $this->bearer->getUser()->id;
        }
        $user = $this->userRepository->update($userId, $request->all());

        return response([
            'user' => Schema::forUser()->adapt($user->toArray())
        ]);
    }
}