<?php

it('gives back successful response for page', function () {
    $response = $this->get('/');

    $response->assertStatus(200);
});
