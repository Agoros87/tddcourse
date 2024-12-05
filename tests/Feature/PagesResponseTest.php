<?php

use App\Models\Course;
use Illuminate\Foundation\Testing\RefreshDatabase;

use function Pest\Laravel\get;

uses(RefreshDatabase::class);
it('gives back successful response for page', function () {
    get(route('home'))
        ->assertOk();
});

it(' gives back successeful for course details page', function () {
    //Arange

    $course = Course::factory()->create();
    get(route('course-details', $course))
        ->assertOk();

    //Act
});
