<?php

namespace CatPaw\Schedule\Services;

use Amp\Loop;
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
        int|DateTime $due,
        callable $action,
    ) {
        $now = new DateTime();

        if (is_int($due)) {
            $delta = $due - $now->getTimestamp();
        } else {
            $delta = $due->getTimestamp() - $now->getTimestamp();
        }
        
        Loop::delay($delta * 1000, $action);
    }
}