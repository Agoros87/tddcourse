<?php

use Illuminate\Foundation\Testing\RefreshDatabase;

uses(RefreshDatabase::class);

it('Shows courses overview',function (){
    //Arrange
    Course::factory()->create(['title' => 'Course A']);
    Course::factory()->create(['title' => 'Course B']);
    Course::factory()->create(['title' => 'Course C']);

    //Act

    \Pest\Laravel\get(route('home'))
        ->assertSeeText(['Course A',
            'Course B',
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
