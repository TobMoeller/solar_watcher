<?php

namespace App\Http\Controllers\Api\V1;

use App\Http\Controllers\Controller;
use App\Http\Requests\Api\V1\StoreInverterRequest;
use App\Http\Requests\Api\V1\UpdateInverterRequest;
use App\Http\Resources\Api\V1\InverterCollection;
use App\Http\Resources\Api\V1\InverterResource;
use App\Models\Inverter;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Resources\Json\JsonResource;
use Illuminate\Support\Facades\Response;

class InverterController extends Controller
{
    public function __construct()
    {
        $this->authorizeResource(Inverter::class);
    }

    public function index(): JsonResource
    {
        return app(InverterCollection::class, ['resource' => Inverter::paginate(10)]);
    }

    public function store(StoreInverterRequest $request): JsonResource
    {
        $inverter = Inverter::create($request->validated());
        return app(InverterResource::class, ['resource' => $inverter]);
    }

    public function show(Inverter $inverter): JsonResource
    {
        return app(InverterResource::class, ['resource' => $inverter]);
    }

    public function update(UpdateInverterRequest $request, Inverter $inverter): JsonResource
    {
        $inverter->update($request->validated());
        return app(InverterResource::class, ['resource' => $inverter]);
    }

    public function destroy(Inverter $inverter): JsonResponse
    {
        $inverter->delete();
        return Response::json(['message' => __('Inverter :id successfully deleted', ['id' => $inverter->id])]);
    }
}
