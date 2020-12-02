<?php

namespace App\Http\Middleware;

use App\Http\Bearer\Bearer;
use App\Http\ResponseError;
use Closure;

class BearerRequire
{
    private $bearer;

    public function __construct(Bearer $bearer)
    {
        $this->bearer = $bearer;
    }

    public function handle($request, Closure $next, ...$guards)
    {
        if (!$this->bearer->hasUser()) {
            return ResponseError::unauthorized();
        }

        return $next($request);
    }
}