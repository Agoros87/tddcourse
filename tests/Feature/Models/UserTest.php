<?php

use App\Models\Course;
use App\Models\User;




it('has courses', function () {
    //Arrange
    $user = User::factory()
        ->has(Course::factory()->count(2))
        ->create();

    //Act
    expect($user->courses)
        ->toHaveCount(2)
        ->each->toBeInstanceOf(Course::class);
    //Assert
});
