<?php

namespace Carsguide\Tests\Middleware;

use Carsguide\ReCaptcha\Middleware\ReCaptchaMiddleware;
use Carsguide\Tests\TestCase;
use Illuminate\Http\Request;
use ReCaptcha\ReCaptcha;

class ReCaptchaMiddlewareTest extends TestCase
{
    public function setUp()
    {
        parent::setUp();

        $this->registerServices();

        $this->middleware = new ReCaptchaMiddleware(new ReCaptcha('recaptchaSecret'));
    }

    /**
     * @test
     * @group ReCaptchaMiddleware
     */
    public function ifReCaptchaRequestEmptyShouldThrowException()
    {
        $request = new Request();

        $this->middleware->handle($request, function () {});

    }
}
