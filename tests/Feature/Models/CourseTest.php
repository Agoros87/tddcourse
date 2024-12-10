<?php

use App\Models\Course;
use App\Models\Video;
use Illuminate\Foundation\Testing\RefreshDatabase;

uses(RefreshDatabase::class);

it('only returns released courses for released scope', function () {
    //Arrange
    Course::factory()->released()->create();
    Course::factory()->create();
    //Act
    expect(Course::released()->get())
        ->toHaveCount(1)
        ->first()->id->toEqual(1);
    //Assert
});

it('has videos', function () {
    //Arrange
    $course = Course::factory()->create();
    $video = Video::factory()->create(['course_id' => $course->id]);

    //Act
    expect($course->videos)
        ->toHaveCount(3)
        ->each()->toBeInstanceOf(Video::class);
});
