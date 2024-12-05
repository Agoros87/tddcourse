<?php

use App\Models\Course;
use App\Models\Video;
use Illuminate\Foundation\Testing\RefreshDatabase;
use function Pest\Laravel\get;

uses(RefreshDatabase::class);

it('shows course details', function () {
    //Arrange
    $course = Course::factory()->create([
        'tagLine' => 'Course tagline',
        'image' => 'image.png',
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
    $course = Course::factory()->create();
    Video::factory()->count(3)->create(['course_id' => $course->id]);

    //Act
    get(route('course-details', $course))
        ->assertOk()
        ->assertSeeText('3 videos');
    //Assert
});
