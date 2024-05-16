<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class InverterStatus extends Model
{
    use HasFactory;

    public $casts = [
        'is_online' => 'boolean',
    ];

    /**
     * @return BelongsTo<Inverter, InverterStatus>
     */
    public function inverter(): BelongsTo
    {
        return $this->belongsTo(Inverter::class);
    }
}
