<?php

namespace Carsguide\ReCaptcha\Providers;

use Illuminate\Support\ServiceProvider;
use ReCaptcha\ReCaptcha;

class ReCaptchaServiceProvider extends ServiceProvider
{
    public function register()
    {
        $this->app->bind('ReCaptcha\ReCaptcha', function ($app) {
            return new ReCaptcha(env('GOOGLE_RECAPTCHA_SECRET'));
        });
    }
}
