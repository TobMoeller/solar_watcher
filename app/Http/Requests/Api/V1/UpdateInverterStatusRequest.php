<?php

namespace App\Http\Requests\Api\V1;

use Illuminate\Foundation\Http\FormRequest;

class UpdateInverterStatusRequest extends FormRequest
{
    /**
     * @return array<string, \Illuminate\Contracts\Validation\ValidationRule|array<mixed>|string>
     */
    public function rules(): array
    {
        return [
            'inverter_id' => ['required', 'int', 'exists:inverters,id'],
            'is_online' => ['required', 'bool'],
            'udc' => ['sometimes', 'nullable', 'decimal:0,2', 'between:0,1000000'],
            'idc' => ['sometimes', 'nullable', 'decimal:0,2', 'between:0,1000000'],
            'pac' => ['sometimes', 'nullable', 'decimal:0,2', 'between:0,1000000'],
            'pdc' => ['sometimes', 'nullable', 'decimal:0,2', 'between:0,1000000'],
        ];
    }
}
