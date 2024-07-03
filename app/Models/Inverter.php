<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Casts\Attribute;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Database\Eloquent\Relations\HasOne;

class Inverter extends Model
{
    use HasFactory;

    public $guarded = [];

    /**
     * @return HasMany<InverterOutput>
     */
    public function outputs(): HasMany
    {
        return $this->hasMany(InverterOutput::class);
    }

    /**
     * @return HasMany<InverterStatus>
     */
    public function statuses(): HasMany
    {
        return $this->hasMany(InverterStatus::class);
    }

    /**
     * @return HasOne<InverterStatus>
     */
    public function latestStatus(): HasOne
    {
        return $this->statuses()->one()->ofMany('recorded_at', 'max');
    }

    /**
     * @return Attribute<bool, bool>
     */
    public function isOnline(): Attribute
    {
        return new Attribute(
            get: fn (): bool => $this->latestStatus?->is_online && $this->latestStatus->recorded_at->greaterThanOrEqualTo(now()->subMinutes(30)),
        );
    }
}
