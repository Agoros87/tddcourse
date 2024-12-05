<?php

use Illuminate\Foundation\Testing\RefreshDatabase;

use function Pest\Laravel\get;

uses(RefreshDatabase::class);
it('gives back successful response for page', function () {
    get(route('home'))
        ->assertOk();
});
