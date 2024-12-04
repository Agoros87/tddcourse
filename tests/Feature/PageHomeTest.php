<?php

use App\Models\Course;
use Illuminate\Foundation\Testing\RefreshDatabase;
use function Pest\Laravel\get;

uses(RefreshDatabase::class);

it('Shows courses overview',function (){
    //Arrange
    Course::factory()->create(['title' => 'Course A','description' => 'Description Course A']);
    Course::factory()->create(['title' => 'Course B','description' => 'Description Course B']);
    Course::factory()->create(['title' => 'Course C','description' => 'Description Course C']);

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

    //Act

    //Assert
});

it('Shows courses by release date', function () {
    //Arrange

    //Act

    //Assert
});
