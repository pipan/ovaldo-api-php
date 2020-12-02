<?php

namespace Lib\Time;

use Carbon\CarbonImmutable;

class TimeAdjusterProxy implements TimeAdjuster
{
    private $timeAdjuster;

    public function __construct(TimeAdjuster $timeAdjuster)
    {
        $this->timeAdjuster = $timeAdjuster;
    }

    public function set(TimeAdjuster $timeAdjuster)
    {
        $this->timeAdjuster = $timeAdjuster;
    }

    public function toClient(CarbonImmutable $carbon): CarbonImmutable
    {
        return $this->timeAdjuster->toClient($carbon);
    }

    public function toServer(CarbonImmutable $carbon): CarbonImmutable
    {
        return $this->timeAdjuster->toServer($carbon);
    }
}