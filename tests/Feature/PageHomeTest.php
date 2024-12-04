<?php

use App\Models\Course;
use Carbon\Carbon;
use Illuminate\Foundation\Testing\RefreshDatabase;
use function Pest\Laravel\get;

uses(RefreshDatabase::class);

it('Shows courses overview',function (){
    //Arrange
    Course::factory()->create(['title' => 'Course A', 'description' => 'Description Course A',
        'released_at' => Carbon::now()]);
    Course::factory()->create(['title' => 'Course B', 'description' => 'Description Course B',
        'released_at' => Carbon::now()]);
    Course::factory()->create(['title' => 'Course C', 'description' => 'Description Course C',
        'released_at' => Carbon::now()]);

    //Act

    get(route('home'))
        ->assertSeeText(['Course A',
            'description Course A',
            'Course B',
            'description Course B',
            'Course C',
            'description Course C'
            ]);

});

it('Shows only release courses', function () {
    //Arrange
    Course::factory()->create(['title' => 'Course A','released_at' => Carbon::yesterday()]);
    Course::factory()->create(['title' => 'Course B']);
    //Act
    get(route('home'))
        ->assertSeeText([
            'Course A',
            ])
        ->assertDontSeeText([
            'Course B'
        ]);


    //Assert
});

it('Shows courses by release date', function () {
    //Arrange
    Course::factory()->create(['title' => 'Course A','released_at' => Carbon::yesterday()]);
    Course::factory()->create(['title' => 'Course B','released_at' => Carbon::now()]);
    //Act
    get(route('home'))
        ->assertSeeTextInOrder([
            'Course B',
            'Course A',
        ]);
    //Assert
});
