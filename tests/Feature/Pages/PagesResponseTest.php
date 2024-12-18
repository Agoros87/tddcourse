<?php

use App\Models\Course;
use App\Models\User;


use App\Models\Video;
use function Pest\Laravel\actingAs;
use function Pest\Laravel\get;


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
    loginAsUser();

    //Act

   get(route('pages.dashboard'))
       ->assertOk();

});

it('does not find Jetstream registration page', function () {
    //Act & Assert
    get('register')
        ->assertNotFound();
});

it('gives successful response for videos page', function () {
    //Arrange
    $course = Course::factory()
        ->has(Video::factory())
        ->create();

    //Act Assert
    loginAsUser();
    get(route('pages.course-videos', $course))
        ->assertOk();
});
