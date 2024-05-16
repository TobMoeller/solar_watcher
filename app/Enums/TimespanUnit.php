<?php

namespace App\Enums;

use Illuminate\Support\Carbon;

enum TimespanUnit: string
{
    case DAY = 'day';
    case MONTH = 'month';
    case YEAR = 'year';

    public function isValidRecordedAtDate(Carbon $date): bool
    {
        return $date->isStartOfDay() &&
            match ($this) {
                self::MONTH => $date->day === 1,
                self::YEAR => $date->dayOfYear === 1,
                default => true,
            };
    }
}
