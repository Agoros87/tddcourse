<?php

use App\Models\Course;
use App\Models\User;
use Carbon\Carbon;
use Illuminate\Database\Eloquent\Factories\Sequence;
use function Pest\Laravel\get;



it('cannot be access by guest', closure: function () {

    //Act
    get(route('pages.dashboard'))
        ->assertRedirect(route('login'));

    //Assert
});

it('list purchased courses', function () {
    //Arrange
    $user = User::factory()
        ->has(Course::factory()->count(2)->state(
            new Sequence(
                ['title' => 'Course A'],
                ['title' => 'Course B']
            )), 'purchasedCourses')
        ->create();
    //Act and Assert
    loginAsUser($user);
    get(route('pages.dashboard'))
        ->assertOk()
        ->assertSeeText([
            'Course A',
            'Course B',
            ]);
});

it('does not list other courses', function () {
    //Arrange

    $course = Course::factory()->create();
    //Act
    loginAsUser();
    get(route('pages.dashboard'))
        ->assertOk()
        ->assertDontSeeText($course->title);
    //Assert
});

it('show latest purchased courses first ', function () {
    //Arrange
    $user = loginAsUser();
    $firstCourse = Course::factory()->create();
    $secondCourse = Course::factory()->create();

    $user->purchasedCourses()->attach($firstCourse, ['created_at' => Carbon::yesterday()]);
    $user->purchasedCourses()->attach($secondCourse, ['created_at' => Carbon::now()]);
    //Act

    get(route('pages.dashboard'))
        ->assertOk()
        ->assertSeeTextInOrder([
            $secondCourse->title,
            $firstCourse->title
        ]);
    //Assert
});

it('includes link to product videos', function () {
    //Arrange
    $user = User::factory()
        ->has(Course::factory(), 'purchasedCourses')
        ->create();
    //Act
    loginAsUser($user);
    get(route('pages.dashboard'))
        ->assertOk()
        ->assertSeeText('Watch videos')
        ->assertSee(route('pages.course-videos', Course::first()));
    //Assert
});

it('includes logout', function () {
    //Arrange & Act
    loginAsUser();
    get(route('pages.dashboard'))
        ->assertOk()
        ->assertSeeText('Log Out')
        ->assertSee(route('logout'));
});
