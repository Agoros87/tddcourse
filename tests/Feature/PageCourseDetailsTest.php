<?php

use App\Models\Course;
use Illuminate\Foundation\Testing\RefreshDatabase;
use function Pest\Laravel\get;

uses(RefreshDatabase::class);

it('shows course details', function () {
    //Arrange
    $course = Course::factory()->create([
        'tagLine' => 'Course tagline',
        'image' => 'course.png',
        'learnings' => [
            'learn laravel router',
            'learn laravel views',
            'learn laravel commands',
        ]
    ]);
    //Act
get(route('course-details', $course))
    ->assertOk()
    ->assertSeeText([
        $course-> title,
        $course-> description,
    'Course tagline',
    'learn laravel router',
            'learn laravel views',
            'learn laravel commands',
    ])
    //Assert
    ->assertSee('image.png');
});

it('shows course video count', function () {
    //Arrange

    //Act

    //Assert
});
