<?php

namespace App\Room;

use Carbon\CarbonImmutable;

class RoomTime
{
    private $start;
    private $end;

    public function __construct(CarbonImmutable $star, CarbonImmutable $end)
    {
        $this->start = $star;
        $this->end = $end;
    }

    public function getStart(): CarbonImmutable
    {
        return $this->start;
    }

    public function getEnd(): CarbonImmutable
    {
        return $this->end;
    }
}