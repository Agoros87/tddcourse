<?php

use App\Models\Course;
use Illuminate\Foundation\Testing\RefreshDatabase;
use illuminate\Support\Carbon;

use function Pest\Laravel\get;

uses(RefreshDatabase::class);

it('Shows courses overview', function () {
    //Arrange

    $firstCourse = Course::factory()->released()->create();
    $secondCourse = Course::factory()->released()->create();
    $thirdCourse = Course::factory()->released()->create();

    //Act

    get(route('home'))
        ->assertSeeText([
            $firstCourse->title,
            $firstCourse->description,
            $secondCourse->title,
            $secondCourse->description,
            $thirdCourse->title,
            $thirdCourse->description,
        ]);

});

it('Shows only release courses', function () {
    //Arrange
    $releasedCourse = Course::factory()->released()->create();
    $unreleasedCourse = Course::factory()->create();

    //Act
    get(route('home'))
        ->assertSeeText([$releasedCourse->title])
        ->assertDontSeeText([$unreleasedCourse->title]);

    //Assert
});

it('Shows courses by release date', function () {
    //Arrange

    $releasedCourse = Course::factory()->released(Carbon::yesterday())->create();
    $newestReleasedCourse = Course::factory()->released(Carbon::now())->create();
    Course::factory()->create(['title' => 'Course A', 'released_at' => Carbon::yesterday()]);
    Course::factory()->create(['title' => 'Course B', 'released_at' => Carbon::now()]);
    //Act
    get(route('home'))
        ->assertSeeTextInOrder([
            $newestReleasedCourse->title,
            $releasedCourse->title,
        ]);
    //Assert
});
