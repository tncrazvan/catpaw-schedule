<?php

namespace CatPaw\Schedule\Services;

use CatPaw\Attributes\Service;
use CatPaw\Queue\Services\QueueService;
use DateTime;

#[Service]
class ScheduleService {
    public function __construct(
        private QueueService $queueService,
    ) {
    }

    public function schedule(
        string|DateTime $dateTime,
        callable $action,
    ) {
        if (is_string($dateTime)) {
            // $dateTime = \Cat
        }
    }
}