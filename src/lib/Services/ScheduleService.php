<?php

namespace CatPaw\Schedule\Services;

use CatPaw\Attributes\Service;
use CatPaw\Queue\Services\QueueService;
use Closure;
use Error;
use Revolt\EventLoop;

#[Service]
class ScheduleService {
    private const PATTERN = '/in\s+([0-9])+\s+(minutes|seconds|hours|months|years)/i';

    public function __construct(
        private QueueService $queueService,
    ) {
    }

    /**
     * @param  string  $due    a human readable string pattern indicating the due time.<br/>
     *                         Example: <br/>
     *                         - `in 2 minutes`
     *                         - `in 1 week`
     *                         - `in 3 months`
     *                         - `in 1 year`
     * @param  Closure $action
     * @throws Error   if the `$due` pattern is invalid.
     * @return void
     */
    public function schedule(
        string $due,
        Closure $action,
    ) {
        if (!preg_match(self::PATTERN, $due, $matches)) {
            throw new Error("Invalid due pattern.");
        }
        
        [$_,$value,$unit] = $matches;

        $unit = match ($unit) {
            'year','years' => 60   * 60 * 24 * 365,
            'month','months' => 60 * 60 * 24 * 30,
            'week','weeks' => 60   * 60 * 24 * 7,
            'day','days' => 60     * 60 * 24,
            'hour','hours' => 60   * 60,
            'minute','minutes' => 60,
            'second','seconds' => 1,
            default => 1,
        };

        if (is_string($unit)) {
            throw new Error("Invalid due unit ($unit).");
        }

        if (!is_numeric($value)) {
            throw new Error("Invalid due value ($value).");
        }

        $value = (int)$value;
        
        $delta = $value * $unit;

        $delta = EventLoop::delay($delta * 1000, $action);
    }
}