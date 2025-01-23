<?php


use App\Models\Course;

it('includes purchased details', function () {
    //Arrange

    $course = Course::factory()->create();

    //Act
    $mail = new \App\Mail\NewPurchasedMail($course);

    //assert
    $mail->assertSeeInText("Thanks for purchasing {$course->title}");
    $mail->assertSeeInText('Login');
    $mail->assertSeeInHtml(route('login'));


});
