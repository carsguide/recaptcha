<?php

namespace Carsguide\Tests\Middleware;

use Carsguide\ReCaptcha\Middleware\ReCaptchaMiddleware;
use Carsguide\Tests\TestCase;
use Exception;
use Illuminate\Http\Request;
use Mockery;
use ReCaptcha\ReCaptcha;
use ReCaptcha\Response;

class ReCaptchaMiddlewareTest extends TestCase
{
    public function setUp(): void
    {
        parent::setUp();

        $this->request = new Request();
    }

    /**
     * @test
     * @group ReCaptchaMiddleware
     */
    public function shouldThrowExceptionIfRecaptchaResponseEmpty()
    {
        $middleware = new ReCaptchaMiddleware(new ReCaptcha('recaptchaSecret'));

        try {
            $middleware->handle($this->request, function () {
            });
        } catch (Exception $e) {
            $this->assertEquals('No ReCaptcha response given', $e->getMessage());
        }
    }

    /**
     * @test
     * @group ReCaptchaMiddleware
     */
    public function shouldReturnTrueIfValidResponse()
    {
        $this->request->merge(['g-recaptcha-response' => 'test response']);

        $response = new Response(true, []);

        $mock = Mockery::mock(ReCaptcha::class);
        $mock->shouldReceive('verify')
            ->andReturn($response);

        $middleware = new ReCaptchaMiddleware($mock);

        $this->assertTrue($middleware->handle($this->request, function () {
            return true;
        }));
    }

    /**
     * @test
     * @group ReCaptchaMiddleware
     */
    public function shouldReturn422IfVerificationFailed()
    {
        $this->request->merge(['g-recaptcha-response' => 'test response']);

        $response = new Response(false, ['invalid-input-response']);

        $mock = Mockery::mock(ReCaptcha::class);
        $mock->shouldReceive('verify')
            ->andReturn($response);

        $middleware = new ReCaptchaMiddleware($mock);

        $statusCode = $middleware->handle($this->request, function () {
        })->status();

        $this->assertEquals(422, $statusCode);
    }
}
