<?php

it('redirects to inverter list', function () {
    $response = $this->get('/');

    $response->assertRedirect('/guests/inverters');
});
