<?php


use App\Jobs\HandlePaddlePurchaseJob;
use Illuminate\Support\Carbon;
use Spatie\WebhookClient\Models\WebhookCall;
use function Pest\Laravel\assertDatabaseCount;
use function Pest\Laravel\post;
use function Pest\Laravel\postJson;
use function PHPUnit\Framework\assertSame;

it('can create a valid Paddle webhook signature', function () {
    //Arrange
    $originalTimestamp = 1718139311;
    [$originalArrBody, $originalSigHeader, $originalRawJsonBody] = getValidPaddleWebhookRequest();

    //Assert
    [$body, $header] = generateValidSignedPaddleWebhookRequest($originalArrBody, $originalTimestamp);
    assertSame(json_encode($body), $originalRawJsonBody);
    assertSame($header, $originalSigHeader);

});

it('stores a Paddle purchase request', function () {
    //Arrange
    assertDatabaseCount(WebhookCall::class, 0);
    [$arrData] = getValidPaddleWebhookRequest();
    // We will have to generate a fresh signature because the timestamp cannot be older
    // than 5 seconds, or our webhook signature validator middleware will block the request
    [$requestBody, $requestHeaders] = generateValidSignedPaddleWebhookRequest($arrData);
    //Act & Assert

    // needed to prevent the checkout url slashes from being escaped
    postJson('webhooks', $requestBody, $requestHeaders);
    // Assert
    assertDatabaseCount(WebhookCall::class, 1);
});

it('does not store a invalid Paddle purchase request', function () {
    //Arrange
    assertDatabaseCount(WebhookCall::class, 0);
    // Act
    post('webhooks', []);
    // Assert
    assertDatabaseCount(WebhookCall::class, 0);

});

it('dispatches a job for a valid paddle request', function () {
    //Arrange
    Queue::fake();

    //Act
    [$arrData] = getValidPaddleWebhookRequest();
    [$requestBody, $requestHeaders] = generateValidSignedPaddleWebhookRequest($arrData);
    postJson('webhooks', $requestBody, $requestHeaders);

    //Assert
    Queue::assertPushed(HandlePaddlePurchaseJob::class);

});

it('does not dispatch a job for invalid paddle request', function () {
    //Arrange
    Queue::fake();
    //Act
    post('webhooks', []);

    //Assert

    Queue::assertNotPushed(HandlePaddlePurchaseJob::class);
});

function getValidPaddleWebhookRequest(): array
{
    $sigHeader = ['Paddle-Signature' =>
        'ts=1718139311;h1=d62b2ab563b97d7c044aa31e53a17cb8141ac277cb2062123c6e60754c17050e'];

    $parsedData = [
        "event_id" => "evt_01jhqxammj6befprxkmb3a8vq8",
        "event_type" => "transaction.completed",
        "occurred_at" => "2025-01-16T15:57:18.866536Z",
        "notification_id" => "ntf_01jhqxamrjbw88rjnxt8qfdz3r",
        "data" => [
            "id" => "txn_01jhqx75gc4svfgejn41cver1y",
            "items" => [
                [
                    "price" => [
                        "id" => "pri_01jhqsmhja6mt8jccqfxsgqans",
                        "name" => "Pago Laravel For Beginners",
                        "type" => "standard",
                        "status" => "active",
                        "quantity" => [
                            "maximum" => 10000,
                            "minimum" => 1
                        ],
                        "tax_mode" => "account_setting",
                        "created_at" => "2025-01-16T14:52:49.098502Z",
                        "product_id" => "pro_01jhqsgbk6gddxbgy0vtwn6gbf",
                        "unit_price" => [
                            "amount" => "1500",
                            "currency_code" => "USD"
                        ],
                        "updated_at" => "2025-01-16T14:52:49.098502Z",
                        "custom_data" => null,
                        "description" => "Pago unico",
                        "trial_period" => null,
                        "billing_cycle" => [
                            "interval" => "month",
                            "frequency" => 1
                        ],
                        "unit_price_overrides" => []
                    ],
                    "price_id" => "pri_01jhqsmhja6mt8jccqfxsgqans",
                    "quantity" => 1,
                    "proration" => null
                ]
            ],
            "origin" => "web",
            "status" => "completed",
            "details" => [
                "totals" => [
                    "fee" => "125",
                    "tax" => "260",
                    "total" => "1500",
                    "credit" => "0",
                    "balance" => "0",
                    "discount" => "0",
                    "earnings" => "1115",
                    "subtotal" => "1240",
                    "grand_total" => "1500",
                    "currency_code" => "USD",
                    "credit_to_balance" => "0"
                ],
                "line_items" => [
                    [
                        "id" => "txnitm_01jhqx7zsqbb42h1qsrnak25p3",
                        "totals" => [
                            "tax" => "260",
                            "total" => "1500",
                            "discount" => "0",
                            "subtotal" => "1240"
                        ],
                        "item_id" => null,
                        "product" => [
                            "id" => "pro_01jhqsgbk6gddxbgy0vtwn6gbf",
                            "name" => "Laravel For Beginners",
                            "type" => "standard",
                            "status" => "active",
                            "image_url" => null,
                            "created_at" => "2025-01-16T14:50:31.91Z",
                            "updated_at" => "2025-01-16T14:50:31.91Z",
                            "custom_data" => [
                                "Product" => "One"
                            ],
                            "description" => "Laravel For Beginners",
                            "tax_category" => "standard"
                        ],
                        "price_id" => "pri_01jhqsmhja6mt8jccqfxsgqans",
                        "quantity" => 1,
                        "tax_rate" => "0.21",
                        "unit_totals" => [
                            "tax" => "260",
                            "total" => "1500",
                            "discount" => "0",
                            "subtotal" => "1240"
                        ],
                        "is_tax_exempt" => false,
                        "revised_tax_exempted" => false
                    ]
                ],
                "payout_totals" => [
                    "fee" => "125",
                    "tax" => "260",
                    "total" => "1500",
                    "credit" => "0",
                    "balance" => "0",
                    "discount" => "0",
                    "earnings" => "1115",
                    "fee_rate" => "0.05",
                    "subtotal" => "1240",
                    "grand_total" => "1500",
                    "currency_code" => "USD",
                    "exchange_rate" => "1",
                    "credit_to_balance" => "0"
                ],
                "tax_rates_used" => [
                    [
                        "totals" => [
                            "tax" => "260",
                            "total" => "1500",
                            "discount" => "0",
                            "subtotal" => "1240"
                        ],
                        "tax_rate" => "0.21"
                    ]
                ],
                "adjusted_totals" => [
                    "fee" => "125",
                    "tax" => "260",
                    "total" => "1500",
                    "earnings" => "1115",
                    "subtotal" => "1240",
                    "grand_total" => "1500",
                    "currency_code" => "USD"
                ]
            ],
            "checkout" => [
                "url" => "https://localhost?_ptxn=txn_01jhqx75gc4svfgejn41cver1y"
            ],
            "payments" => [
                [
                    "amount" => "1500",
                    "status" => "captured",
                    "created_at" => "2025-01-16T15:57:13.579629Z",
                    "error_code" => null,
                    "captured_at" => "2025-01-16T15:57:16.019981Z",
                    "method_details" => [
                        "card" => [
                            "type" => "visa",
                            "last4" => "4242",
                            "expiry_year" => 2025,
                            "expiry_month" => 5,
                            "cardholder_name" => "pepito palotes"
                        ],
                        "type" => "card"
                    ],
                    "payment_method_id" => "paymtd_01jhqxafes3g57gztr5bnp358v",
                    "payment_attempt_id" => "a83e765a-9d78-4850-9053-5736d90012a7",
                    "stored_payment_method_id" => "6ef9225d-3c85-4ee4-a85f-4c4054146520"
                ]
            ],
            "billed_at" => "2025-01-16T15:57:16.361422Z",
            "address_id" => "add_01jhqx7zgzeyvy77vkgywd8mcg",
            "created_at" => "2025-01-16T15:55:25.115819Z",
            "invoice_id" => "inv_01jhqxaje5mky1tzshd0t6aqx4",
            "updated_at" => "2025-01-16T15:57:18.486274468Z",
            "business_id" => null,
            "custom_data" => null,
            "customer_id" => "ctm_01jhqx7zgdpqjrs1hp1gbf65zr",
            "discount_id" => null,
            "receipt_data" => null,
            "currency_code" => "USD",
            "billing_period" => [
                "ends_at" => "2025-02-16T15:57:16.019981Z",
                "starts_at" => "2025-01-16T15:57:16.019981Z"
            ],
            "invoice_number" => "10631-10001",
            "billing_details" => null,
            "collection_mode" => "automatic",
            "subscription_id" => "sub_01jhqxajbsbtp2g2mb35jdn5mg"
        ]
    ];

    $rawJsonBOdy = '{"event_id":"evt_01jhqxammj6befprxkmb3a8vq8","event_type":"transaction.completed","occurred_at":"2025-01-16T15:57:18.866536Z","notification_id":"ntf_01jhqxamrjbw88rjnxt8qfdz3r","data":{"id":"txn_01jhqx75gc4svfgejn41cver1y","items":[{"price":{"id":"pri_01jhqsmhja6mt8jccqfxsgqans","name":"Pago Laravel For Beginners","type":"standard","status":"active","quantity":{"maximum":10000,"minimum":1},"tax_mode":"account_setting","created_at":"2025-01-16T14:52:49.098502Z","product_id":"pro_01jhqsgbk6gddxbgy0vtwn6gbf","unit_price":{"amount":"1500","currency_code":"USD"},"updated_at":"2025-01-16T14:52:49.098502Z","custom_data":null,"description":"Pago unico","trial_period":null,"billing_cycle":{"interval":"month","frequency":1},"unit_price_overrides":[]},"price_id":"pri_01jhqsmhja6mt8jccqfxsgqans","quantity":1,"proration":null}],"origin":"web","status":"completed","details":{"totals":{"fee":"125","tax":"260","total":"1500","credit":"0","balance":"0","discount":"0","earnings":"1115","subtotal":"1240","grand_total":"1500","currency_code":"USD","credit_to_balance":"0"},"line_items":[{"id":"txnitm_01jhqx7zsqbb42h1qsrnak25p3","totals":{"tax":"260","total":"1500","discount":"0","subtotal":"1240"},"item_id":null,"product":{"id":"pro_01jhqsgbk6gddxbgy0vtwn6gbf","name":"Laravel For Beginners","type":"standard","status":"active","image_url":null,"created_at":"2025-01-16T14:50:31.91Z","updated_at":"2025-01-16T14:50:31.91Z","custom_data":{"Product":"One"},"description":"Laravel For Beginners","tax_category":"standard"},"price_id":"pri_01jhqsmhja6mt8jccqfxsgqans","quantity":1,"tax_rate":"0.21","unit_totals":{"tax":"260","total":"1500","discount":"0","subtotal":"1240"},"is_tax_exempt":false,"revised_tax_exempted":false}],"payout_totals":{"fee":"125","tax":"260","total":"1500","credit":"0","balance":"0","discount":"0","earnings":"1115","fee_rate":"0.05","subtotal":"1240","grand_total":"1500","currency_code":"USD","exchange_rate":"1","credit_to_balance":"0"},"tax_rates_used":[{"totals":{"tax":"260","total":"1500","discount":"0","subtotal":"1240"},"tax_rate":"0.21"}],"adjusted_totals":{"fee":"125","tax":"260","total":"1500","earnings":"1115","subtotal":"1240","grand_total":"1500","currency_code":"USD"}},"checkout":{"url":"https:\/\/localhost?_ptxn=txn_01jhqx75gc4svfgejn41cver1y"},"payments":[{"amount":"1500","status":"captured","created_at":"2025-01-16T15:57:13.579629Z","error_code":null,"captured_at":"2025-01-16T15:57:16.019981Z","method_details":{"card":{"type":"visa","last4":"4242","expiry_year":2025,"expiry_month":5,"cardholder_name":"pepito palotes"},"type":"card"},"payment_method_id":"paymtd_01jhqxafes3g57gztr5bnp358v","payment_attempt_id":"a83e765a-9d78-4850-9053-5736d90012a7","stored_payment_method_id":"6ef9225d-3c85-4ee4-a85f-4c4054146520"}],"billed_at":"2025-01-16T15:57:16.361422Z","address_id":"add_01jhqx7zgzeyvy77vkgywd8mcg","created_at":"2025-01-16T15:55:25.115819Z","invoice_id":"inv_01jhqxaje5mky1tzshd0t6aqx4","updated_at":"2025-01-16T15:57:18.486274468Z","business_id":null,"custom_data":null,"customer_id":"ctm_01jhqx7zgdpqjrs1hp1gbf65zr","discount_id":null,"receipt_data":null,"currency_code":"USD","billing_period":{"ends_at":"2025-02-16T15:57:16.019981Z","starts_at":"2025-01-16T15:57:16.019981Z"},"invoice_number":"10631-10001","billing_details":null,"collection_mode":"automatic","subscription_id":"sub_01jhqxajbsbtp2g2mb35jdn5mg"}}';
    return [$parsedData, $sigHeader, $rawJsonBOdy];
}

function generateValidSignedPaddleWebhookRequest(array $data, ?int $timestamp = null): array
{
    $ts = $timestamp ?? Carbon::now()->unix();
    $secret = config('services.paddle.notification-endpoint-secret-key');
    $rawJsonBody = json_encode($data);
    $calculatedSig = hash_hmac('sha256', "{$ts}:{$rawJsonBody}", $secret);
    $header = [
        'Paddle-Signature' => "ts={$ts};h1={$calculatedSig}",
    ];
    return [$data, $header];
}
