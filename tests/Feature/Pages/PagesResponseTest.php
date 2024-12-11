<?php

use App\Models\Course;
use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;

use function Pest\Laravel\actingAs;
use function Pest\Laravel\get;

uses(RefreshDatabase::class);
it('gives back successful response for page', function () {
    get(route('pages.home'))
        ->assertOk();
});

it(' gives back successeful for course details page', function () {
    //Arange

    $course = Course::factory()->released()->create();
    get(route('pages.course-details', $course))
        ->assertOk();

    //Act
});

it('gives back successful for dashboard page', function () {
    //Arrange
    $user = User::factory()->create();

    //Act
   $this->actingAs($user);
   get(route('dashboard'))
       ->assertOk();

});
