<?php

namespace Tests\Feature\Fakes;

class TwitterFake
{
    protected array $tweets = [];

    public function tweet(string $status): array
    {
        $this->tweets[] = $status;

        return [
            'status' => $status,
        ];
    }

    public function assertTweetSent(string $status): self
    {
        \PHPUnit\Framework\Assert::assertContains($status, $this->tweets);

        return $this;
    }
}
