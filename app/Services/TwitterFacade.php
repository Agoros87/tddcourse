<?php

namespace App\Services;

use Illuminate\Support\Facades\Facade;
use Tests\Feature\Fakes\TwitterFake;


class TwitterFacade extends Facade
{
    protected static function getFacadeAccessor()
    {
        return TwitterClientInterface::class;
    }

    public static function fake()
    {
    self::swap(new TwitterFake);
    }

}
