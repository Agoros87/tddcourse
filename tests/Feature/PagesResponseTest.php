<?php

use function Pest\Laravel\get;

it('gives back successful response for page', function () {
    get(route('home'))
        ->assertOk();
});
