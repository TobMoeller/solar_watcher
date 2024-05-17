<?php

use App\Models\Inverter;
use App\Models\InverterOutput;
use App\Models\User;
use Laravel\Sanctum\Sanctum;

use function Pest\Laravel\assertDatabaseHas;
use function Pest\Laravel\assertDatabaseMissing;
use function Pest\Laravel\deleteJson;
use function Pest\Laravel\getJson;
use function Pest\Laravel\postJson;
use function Pest\Laravel\putJson;

beforeEach(function () {
    $this->user = User::factory()->create();
});

it('is unauthorized', function () {
    Sanctum::actingAs($this->user);

    $inverterOutput = InverterOutput::factory()->create();

    getJson(route('api.v1.inverter-outputs.index'))
        ->assertForbidden();

    getJson(route('api.v1.inverter-outputs.show', ['inverter_output' => $inverterOutput]))
        ->assertForbidden();

    postJson(route('api.v1.inverter-outputs.store'), $inverterOutput->toArray())
        ->assertForbidden();

    putJson(route('api.v1.inverter-outputs.update', ['inverter_output' => $inverterOutput]), $inverterOutput->toArray())
        ->assertForbidden();

    deleteJson(route('api.v1.inverter-outputs.destroy', ['inverter_output' => $inverterOutput]))
        ->assertForbidden();
});

it('show all inverter outputs', function () {
    Sanctum::actingAs($this->user, ['inverters:viewAny']);

    $inverterOutput = InverterOutput::factory(30)->create();

    getJson(route('api.v1.inverter-outputs.index', ['page' => 2]))
        ->assertOk()
        ->assertJson(['data' => $inverterOutput->where('id', '>', 10)->take(10)->values()->toArray()]);
});

it('shows a single inverter output', function () {
    Sanctum::actingAs($this->user, ['inverters:view']);

    $inverterOutput = InverterOutput::factory()->create();

    getJson(route('api.v1.inverter-outputs.show', ['inverter_output' => $inverterOutput]))
        ->assertOk()
        ->assertJson(['data' => $inverterOutput->toArray()]);
});

it('stores an inverter output', function () {
    Sanctum::actingAs($this->user, ['inverters:create']);

    $inverterOutput = InverterOutput::factory()->make();

    postJson(route('api.v1.inverter-outputs.store'), $inverterOutput->toArray())
        ->assertCreated()
        ->assertJson(['data' => $inverterOutput->toArray()]);

    assertDatabaseHas('inverter_outputs', $inverterOutput->toArray());
});

it('updates an inverter output', function () {
    Sanctum::actingAs($this->user, ['inverters:update']);

    $inverterOutput = InverterOutput::factory()->create();
    $inverterOutputData = InverterOutput::factory()->make();

    putJson(route('api.v1.inverter-outputs.update', ['inverter_output' => $inverterOutput]), $inverterOutputData->toArray())
        ->assertOk()
        ->assertJson(['data' => $inverterOutputData->toArray()]);

    expect($inverterOutput->fresh())
        ->toArray()->toMatchArray($inverterOutputData->toArray());
});

it('deletes an inverter output', function () {
    Sanctum::actingAs($this->user, ['inverters:delete']);

    $inverterOutput = InverterOutput::factory()->create();

    deleteJson(route('api.v1.inverter-outputs.destroy', ['inverter_output' => $inverterOutput]))
        ->assertOk();

    assertDatabaseMissing('inverter_outputs', ['id' => $inverterOutput->id]);
});

