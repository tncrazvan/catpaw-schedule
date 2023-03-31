<?php

namespace CatPaw\Schedule\Services;

use Amp\Loop;
use CatPaw\Attributes\Service;
use CatPaw\Queue\Services\QueueService;
use Error;

#[Service]
class ScheduleService {
    private const PATTERN = '/in\s+([0-9])+\s+(minutes|seconds|hours|months|years)/i';

    public function __construct(
        private QueueService $queueService,
    ) {
    }

    /**
     * @param  string   $due    a human readable string pattern indicating the due time.<br/>
     *                          Example: <br/>
     *                          - `in 2 minutes`
     *                          - `in 1 week`
     *                          - `in 3 months`
     *                          - `in 1 year`
     * @param  callable $action
     * @throws Error    if the `$due` pattern is invalid.
     * @return void
     */
    public function schedule(
        string $due,
        callable $action,
    ) {
        if (!preg_match(self::PATTERN, $due, $matches)) {
            throw new Error("Invalid due pattern.");
        }
        
        [$_,$value,$unit] = $matches;

        $unit = match ($unit) {
            'year','years' => 1000     * 60 * 60 * 24 * 365,
            'month','months' => 1000   * 60 * 60 * 24 * 30,
            'week','weeks' => 1000     * 60 * 60 * 24 * 7,
            'day','days' => 1000       * 60 * 60 * 24,
            'hour','hours' => 1000     * 60 * 60,
            'minute','minutes' => 1000 * 60,
            'second','seconds' => 1000,
            default => $unit,
        };

        if (is_string($unit)) {
            throw new Error("Invalid due unit ($unit).");
        }

        if (!is_numeric($value)) {
            throw new Error("Invalid due value ($value).");
        }

        $value = (int)$value;
        
        $delta = $value * $unit;

        $delta = Loop::delay($delta * 1000, $action);
    }
}