<?php

use function Pest\Laravel\artisan;
use function Pest\Laravel\assertDatabaseHas;

test('creates a user', function () {
    artisan('app:create-user')
        ->expectsQuestion(__('Name'), '::name::')
        ->expectsQuestion(__('Email'), 'foo@bar.de')
        ->expectsQuestion(__('Password'), 'password')
        ->expectsQuestion(__('Confirm Password'), 'password')
        ->expectsOutputToContain('created successfully')
        ->assertOk();

    assertDatabaseHas('users', [
        'name' => '::name::',
        'email' => 'foo@bar.de'
    ]);
});
