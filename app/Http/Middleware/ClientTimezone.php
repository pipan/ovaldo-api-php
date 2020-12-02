<?php

namespace App\Http\Middleware;

use Closure;
use Lib\Time\OffsetTimeAdjuster;
use Lib\Time\TimeAdjusterProxy;

class ClientTimezone
{
    private $timeAdjuster;

    public function __construct(TimeAdjusterProxy $timeAdjuster)
    {
        $this->timeAdjuster = $timeAdjuster;
    }

    public function handle($request, Closure $next, ...$guards)
    {
        $offset = $request->header('x-timezone-offset');
        $this->timeAdjuster->set(
            new OffsetTimeAdjuster($offset * -1)
        );

        return $next($request);
    }
}