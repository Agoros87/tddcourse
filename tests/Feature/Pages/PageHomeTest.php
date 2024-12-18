<?php

use App\Models\Course;
use illuminate\Support\Carbon;

use function Pest\Laravel\get;



it('Shows courses overview', function () {
    //Arrange

    $firstCourse = Course::factory()->released()->create();
    $secondCourse = Course::factory()->released()->create();
    $thirdCourse = Course::factory()->released()->create();

    //Act

    get(route('pages.home'))
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
    get(route('pages.home'))
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
    get(route('pages.home'))
        ->assertSeeTextInOrder([
            $newestReleasedCourse->title,
            $releasedCourse->title,
        ]);
    //Assert
});

it('includes login if not logged in', function () {

    //Act Assert

    get(route('pages.home'))
        ->assertOk()
        ->assertSeeText('Login')
        ->assertSee(route('login'));
});


it('includes logout if logged in', function () {
    //Arrange
    loginAsUser();
    //Act
    get(route('pages.home'))
        ->assertOk()
        ->assertSeeText('Log out')
        ->assertSee(route('logout'));
    // Assert
});
