<?php

namespace App\Http\Controllers;

use App\Http\Bearer\Bearer;
use App\Http\Schema;

class StatusController extends Controller
{
    public function __invoke(Bearer $bearer)
    {
        $user = $bearer->getUser();
        return response([
            'user' => $user ? Schema::forUser()->adapt($user->toArray()) : null
        ]);
    }
}
