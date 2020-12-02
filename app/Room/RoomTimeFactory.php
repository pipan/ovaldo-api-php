<?php

namespace App\Room;

use Carbon\CarbonImmutable;
use Lib\Time\TimeAdjuster;

class RoomTimeFactory
{
    private $timeAdjuster;

    public function __construct(TimeAdjuster $timeAdjuster)
    {
        $this->timeAdjuster = $timeAdjuster;
    }

    public function fromNow(): RoomTime
    {
        return $this->fromServerTime(CarbonImmutable::now());
    }

    public function fromServerTime(CarbonImmutable $datetime): RoomTime
    {
        $clientDatetime = $this->timeAdjuster->toClient($datetime);
        return $this->fromClientDay($clientDatetime->year, $clientDatetime->month, $clientDatetime->day);
    }

    public function fromClientDay($year, $month, $day): RoomTime
    {
        $start = $this->timeAdjuster->toServer(
            CarbonImmutable::createFromDate($year, $month, $day)->setTime(0, 0, 0)
        );

        return new RoomTime($start, $start->addDay());
    }
}