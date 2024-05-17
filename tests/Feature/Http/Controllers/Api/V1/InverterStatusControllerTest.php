<?php

use App\Models\InverterStatus;
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

    $inverterStatus = InverterStatus::factory()->create();

    getJson(route('api.v1.inverter-status.index'))
        ->assertForbidden();

    getJson(route('api.v1.inverter-status.show', ['inverter_status' => $inverterStatus]))
        ->assertForbidden();

    postJson(route('api.v1.inverter-status.store'), $inverterStatus->toArray())
        ->assertForbidden();

    putJson(route('api.v1.inverter-status.update', ['inverter_status' => $inverterStatus]), $inverterStatus->toArray())
        ->assertForbidden();

    deleteJson(route('api.v1.inverter-status.destroy', ['inverter_status' => $inverterStatus]))
        ->assertForbidden();
});

it('show all inverter outputs', function () {
    Sanctum::actingAs($this->user, ['inverters:viewAny']);

    $inverterStatus = InverterStatus::factory(30)->create();

    getJson(route('api.v1.inverter-status.index', ['page' => 2]))
        ->assertOk()
        ->assertJson(['data' => $inverterStatus->where('id', '>', 10)->take(10)->values()->toArray()]);
});

it('shows a single inverter output', function () {
    Sanctum::actingAs($this->user, ['inverters:view']);

    $inverterStatus = InverterStatus::factory()->create();

    getJson(route('api.v1.inverter-status.show', ['inverter_status' => $inverterStatus]))
        ->assertOk()
        ->assertJson(['data' => $inverterStatus->toArray()]);
});

it('stores an inverter output', function () {
    Sanctum::actingAs($this->user, ['inverters:create']);

    $inverterStatus = InverterStatus::factory()->make();

    postJson(route('api.v1.inverter-status.store'), $inverterStatus->toArray())
        ->assertCreated()
        ->assertJson(['data' => $inverterStatus->toArray()]);

    assertDatabaseHas('inverter_statuses', $inverterStatus->toArray());
});

it('updates an inverter output', function () {
    Sanctum::actingAs($this->user, ['inverters:update']);

    $inverterStatus = InverterStatus::factory()->create();
    $inverterOutputData = InverterStatus::factory()->make();

    putJson(route('api.v1.inverter-status.update', ['inverter_status' => $inverterStatus]), $inverterOutputData->toArray())
        ->assertOk()
        ->assertJson(['data' => $inverterOutputData->toArray()]);

    expect($inverterStatus->fresh())
        ->toArray()->toMatchArray($inverterOutputData->toArray());
});

it('deletes an inverter output', function () {
    Sanctum::actingAs($this->user, ['inverters:delete']);

    $inverterStatus = InverterStatus::factory()->create();

    deleteJson(route('api.v1.inverter-status.destroy', ['inverter_status' => $inverterStatus]))
        ->assertOk();

    assertDatabaseMissing('inverter_statuses', ['id' => $inverterStatus->id]);
});


