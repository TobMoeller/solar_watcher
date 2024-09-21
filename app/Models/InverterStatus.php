<?php

namespace App\Models;

use Database\Factories\InverterStatusFactory;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class InverterStatus extends Model
{
    /** @use HasFactory<InverterStatusFactory> */
    use HasFactory;

    public $casts = [
        'is_online' => 'boolean',
        'recorded_at' => 'datetime',
    ];

    public $guarded = [];

    /**
     * @return BelongsTo<Inverter, InverterStatus>
     */
    public function inverter(): BelongsTo
    {
        return $this->belongsTo(Inverter::class);
    }
}
