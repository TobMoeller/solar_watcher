<?php

namespace App\Http\Requests\Api\V1;

use App\Enums\TimespanUnit;
use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Validation\Rule;

class UpdateInverterOutputRequest extends FormRequest
{
    /**
     * @return array<string, \Illuminate\Contracts\Validation\ValidationRule|array<mixed>|string>
     */
    public function rules(): array
    {
        return [
            'inverter_id' => ['required', 'int', 'exists:inverters,id'],
            'output' => ['required', 'decimal:0,2', 'between:0,1000000'],
            'timespan' => ['required', Rule::enum(TimespanUnit::class)],
            'recorded_at' => ['required', 'date'],
        ];
    }
}
