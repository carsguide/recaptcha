<?php

namespace Carsguide\ReCaptcha\Facades;

use Illuminate\Support\Facades\Facade;

class ReCaptcha extends Facade
{
    protected static function getFacadeAccessor()
    {
        return 'recaptcha';
    }
}
