<?php


use App\Jobs\HandlePaddlePurchaseJob;
use App\Models\Course;
use App\Models\PurchasedCourse;
use App\Models\User;
use function Pest\Laravel\assertDatabaseHas;

it('store paddle purchased', function () {
    //Assert
    $this->assertDatabaseEmpty(User::class);
    $this->assertDatabaseEmpty(PurchasedCourse::class);
    //Arrange

    $course = Course::factory()->create([
        'paddle_product_id' => 'pro_01jhqsgbk6gddxbgy0vtwn6gbf',
    ]);
    $webhookCall = \Spatie\WebhookClient\Models\WebhookCall::create([
        'name' => 'default',
        'url' => 'some-url',
        'payload' => [
            'email' => 'test@test.es',
            'name' => 'Test user',
            'p_product_id' => 'pro_01jhqsgbk6gddxbgy0vtwn6gbf',
        ]

    ]);
    //Act
    (new HandlePaddlePurchaseJob($webhookCall))->handle();

    //Assert
    assertDatabaseHas(User::class, [
        'email' => 'test@test.es',
        'name' => 'Test user',
    ]);

    $user = User::where('email', 'test@test.es')->first();
    assertDatabaseHas(PurchasedCourse::class, [
        'user_id' => $user->id,
        'course_id' => $course->id,
    ]);
});


it('store paddle purchased for given user', function () {
    //Arrange

    //Act & Assert
});

it('sends a user email', function () {
    //Arrange

    //Act & Assert
});
