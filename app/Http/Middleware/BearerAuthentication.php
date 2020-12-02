<?php

namespace App\Http\Middleware;

use App\Http\Bearer\BearerProxy;
use App\Http\Bearer\SimpleBearer;
use App\Repository\GuestRepository;
use Closure;
use Illuminate\Support\Facades\Cookie;

class BearerAuthentication
{
    private $guestRepository;
    private $bearerProxy;

    public function __construct(GuestRepository $guestRepository, BearerProxy $bearerProxy)
    {
        $this->guestRepository = $guestRepository;
        $this->bearerProxy = $bearerProxy;
    }

    public function handle($request, Closure $next, ...$guards)
    {
        $hash = $request->cookie('user_hash');
        $guest = $this->guestRepository->findByHash($hash);
        if (!$guest) {
            return $next($request);
        }

        $guest = $this->guestRepository->update($guest->id, [
            'accessed_at' => date("Y-m-d H:i:s")
        ]);
        
        Cookie::queue(Cookie::make('user_hash', $hash, 30 * 24 * 60));
        $this->bearerProxy->setBearer(
            new SimpleBearer($guest->user)
        );

        return $next($request);
    }
}