<?php

namespace Lib\Time;

use Carbon\CarbonImmutable;

interface TimeAdjuster
{
    public function toClient(CarbonImmutable $carbon): CarbonImmutable;
    public function toServer(CarbonImmutable $carbon): CarbonImmutable;
}