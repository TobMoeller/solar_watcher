<?php

use App\Models\Inverter;
use App\Models\User;
use Laravel\Sanctum\Sanctum;

use function Pest\Laravel\assertDatabaseHas;
use function Pest\Laravel\deleteJson;
use function Pest\Laravel\getJson;
use function Pest\Laravel\postJson;
use function Pest\Laravel\putJson;

beforeEach(function () {
    $this->user = User::factory()->create();
});

it('is unauthorized', function () {
    Sanctum::actingAs($this->user);

    $inverter = Inverter::factory()->create();

    getJson(route('api.v1.inverters.index'))
        ->assertForbidden();

    getJson(route('api.v1.inverters.show', ['inverter' => $inverter]))
        ->assertForbidden();

    postJson(route('api.v1.inverters.store'), $inverter->toArray())
        ->assertForbidden();

    putJson(route('api.v1.inverters.update', ['inverter' => $inverter]), $inverter->toArray())
        ->assertForbidden();

    deleteJson(route('api.v1.inverters.destroy', ['inverter' => $inverter]))
        ->assertForbidden();
});

it('show all inverters', function () {
    Sanctum::actingAs($this->user, ['inverters:viewAny']);

    $inverters = Inverter::factory(20)->create();

    getJson(route('api.v1.inverters.index'))
        ->assertOk()
        ->assertJson(['data' => $inverters->take(10)->toArray()]);
});

it('shows a single inverter', function () {
    Sanctum::actingAs($this->user, ['inverters:view']);

    $inverter = Inverter::factory()->create();

    getJson(route('api.v1.inverters.show', ['inverter' => $inverter]))
        ->assertOk()
        ->assertJson(['data' => $inverter->toArray()]);
});

it('stores an inverter', function () {
    Sanctum::actingAs($this->user, ['inverters:create']);

    $inverter = Inverter::factory()->make();

    postJson(route('api.v1.inverters.store'), $inverter->toArray())
        ->assertCreated()
        ->assertJson(['data' => $inverter->toArray()]);

    assertDatabaseHas('inverters', $inverter->toArray());
});

it('updates an inverter', function () {
    Sanctum::actingAs($this->user, ['inverters:update']);

    $inverter = Inverter::factory(['name' => '::old_name::'])->create();
    $inverterData = Inverter::factory(['name' => '::new_name::'])->make();

    putJson(route('api.v1.inverters.update', ['inverter' => $inverter]), $inverterData->toArray())
        ->assertOk()
        ->assertJson(['data' => $inverterData->toArray()]);

    expect($inverter->fresh())
        ->toArray()->toMatchArray($inverterData->toArray());
});

it('deletes an inverter', function () {
    Sanctum::actingAs($this->user, ['inverters:delete']);

    $inverter = Inverter::factory()->create();

    deleteJson(route('api.v1.inverters.destroy', ['inverter' => $inverter]))
        ->assertOk();
});
