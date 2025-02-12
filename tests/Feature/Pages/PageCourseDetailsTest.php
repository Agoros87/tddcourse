<?php

use App\Models\Course;
use App\Models\Video;

use function Pest\Laravel\get;

it('does not find unrealeased couse', function () {
    // Arrange
    $course = Course::factory()->create();
    get(route('pages.course-details', $course))
        ->assertNotFound();
});

it('shows course details', function () {
    // Arrange
    $course = Course::factory()->released()->create();

    // Act
    get(route('pages.course-details', $course))
        ->assertOk()
        ->assertSeeText([
            $course->title,
            $course->description,
            $course->tagline,
            ...$course->learnings,
        ])
        ->assertSee(asset("images/{$course->image_name}"));
    //Assert
});

it('shows course video count', function () {

    //Arrange
    $course = Course::factory()
        ->released()
        ->has(Video::factory()->count(3))
        ->create();

    //Arrange
    get(route('pages.course-details', $course))
        ->assertOk()
        ->assertSeeText('3 videos');
    //Assert
});

it('includes paddle checkout button', function () {
    // Arrange
    config()->set('services.paddle.vendor-id', 'vendor-id');
    $course = Course::factory()
        ->released()
        ->create([
            'paddle_product_id' => 'pri_01jhqsmhja6mt8jccqfxsgqans',
        ]);
    // Act & Assert
    get(route('pages.course-details', $course))
        ->assertOk()
        ->assertSee('<script src="https://cdn.paddle.com/paddle/v2/paddle.js"></script>', false)
        ->assertSee('Paddle.Initialize({ token: "vendor-id" });', false)
        ->assertSee('<a href="#" class="paddle_button" data-theme="light"', false);
});
