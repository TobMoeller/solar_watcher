<?php

namespace App\Http\Controllers\Api\V1;

use App\Http\Controllers\Controller;
use App\Http\Requests\Api\V1\StoreInverterStatusRequest;
use App\Http\Requests\Api\V1\UpdateInverterStatusRequest;
use App\Http\Resources\Api\V1\InverterStatusCollection;
use App\Http\Resources\Api\V1\InverterStatusResource;
use App\Models\InverterStatus;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Resources\Json\JsonResource;
use Illuminate\Support\Facades\Response;

class InverterStatusController extends Controller
{
    public function __construct()
    {
        $this->authorizeResource(InverterStatus::class);
    }

    public function index(): JsonResource
    {
        return app(InverterStatusCollection::class, ['resource' => InverterStatus::paginate(10)]);
    }

    public function store(StoreInverterStatusRequest $request): JsonResource
    {
        $inverterStatus = InverterStatus::create($request->validated());
        return app(InverterStatusResource::class, ['resource' => $inverterStatus]);
    }

    public function show(InverterStatus $inverterStatus): JsonResource
    {
        return app(InverterStatusResource::class, ['resource' => $inverterStatus]);
    }

    public function update(UpdateInverterStatusRequest $request, InverterStatus $inverterStatus): JsonResource
    {
        $inverterStatus->update($request->validated());
        return app(InverterStatusResource::class, ['resource' => $inverterStatus]);
    }

    public function destroy(InverterStatus $inverterStatus): JsonResponse
    {
        $inverterStatus->delete();

        return Response::json(['message' => __('Inverter Status :id successfully deleted', ['id' => $inverterStatus->id])]);
    }
}
