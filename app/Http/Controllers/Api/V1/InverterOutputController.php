<?php

namespace App\Http\Controllers\Api\V1;

use App\Http\Controllers\Controller;
use App\Http\Requests\Api\V1\StoreInverterOutputRequest;
use App\Http\Requests\Api\V1\UpdateInverterOutputRequest;
use App\Http\Resources\Api\V1\InverterOutputCollection;
use App\Http\Resources\Api\V1\InverterOutputResource;
use App\Models\InverterOutput;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Resources\Json\JsonResource;
use Illuminate\Support\Facades\Response;

class InverterOutputController extends Controller
{
    public function __construct()
    {
        $this->authorizeResource(InverterOutput::class);
    }

    public function index(): JsonResource
    {
        return app(InverterOutputCollection::class, ['resource' => InverterOutput::paginate(10)]);
    }

    public function store(StoreInverterOutputRequest $request): JsonResource
    {
        $inverterOutput = InverterOutput::create($request->validated());

        return app(InverterOutputResource::class, ['resource' => $inverterOutput]);
    }

    public function show(InverterOutput $inverterOutput): JsonResource
    {
        return app(InverterOutputResource::class, ['resource' => $inverterOutput]);
    }

    public function update(UpdateInverterOutputRequest $request, InverterOutput $inverterOutput): JsonResource
    {
        $inverterOutput->update($request->validated());

        return app(InverterOutputResource::class, ['resource' => $inverterOutput]);
    }

    public function destroy(InverterOutput $inverterOutput): JsonResponse
    {
        $inverterOutput->delete();

        return Response::json(['message' => __('Inverter Output :id successfully deleted', ['id' => $inverterOutput->id])]);
    }
}
