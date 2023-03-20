<?php

namespace CatPaw\Schedule\Services;

use CatPaw\Attributes\Service;
use CatPaw\Queue\Services\QueueService;
use CatPaw\Utilities\StandardDateFormat;
use DateTime;

#[Service]
class ScheduleService {
    public function __construct(
        private QueueService $queueService,
    ) {
    }

    public function schedule(
        int|string|DateTime $dateTime,
        callable $action,
    ) {
        if(is_int($dateTime)) {
            $dateTime = StandardDateFormat::dateTime()
        } else if (is_string($dateTime)) {
            $dateTime = StandardDateFormat::
        }
    }
}