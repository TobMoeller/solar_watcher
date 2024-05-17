<?php

namespace App\Models;

use App\Enums\TimespanUnit;
use Illuminate\Contracts\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class InverterOutput extends Model
{
    use HasFactory;

    public $casts = [
        'recorded_at' => 'date:Y-m-d',
        'timespan' => TimespanUnit::class,
    ];

    public $guarded = [];

    /**
     * @return BelongsTo<Inverter, InverterOutput>
     */
    public function inverter(): BelongsTo
    {
        return $this->belongsTo(Inverter::class);
    }

    public function scopeUpdatedToday(Builder $query): void
    {
        $query->whereDate('updated_at', now());
    }
}
