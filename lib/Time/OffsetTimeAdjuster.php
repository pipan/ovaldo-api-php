<?php

namespace Lib\Time;

use Carbon\CarbonImmutable;

class OffsetTimeAdjuster implements TimeAdjuster
{
    private $offset;

    public function __construct($offsetMinutes)
    {
        $this->offset = $offsetMinutes;
    }

    public function toClient(CarbonImmutable $carbon): CarbonImmutable
    {
        return $carbon->addMinutes($this->offset);
    }

    public function toServer(CarbonImmutable $carbon): CarbonImmutable
    {
        return $carbon->subMinutes($this->offset);
    }
}