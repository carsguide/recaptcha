<?php

namespace Carsguide\ReCaptcha\Middleware;

use Closure;
use Exception;
use Illuminate\Http\JsonResponse;
use ReCaptcha\ReCaptcha;

class ReCaptchaMiddleware
{
    /**
     * ReCaptcha object
     */
    protected $reCaptcha;

    /**
     * ReCaptcha middleware constructor
     *
     * @param ReCaptcha $reCaptcha
     * @return void
     */
    public function __construct(ReCaptcha $reCaptcha)
    {
        $this->reCaptcha = $reCaptcha;
    }

    /**
     * Handle an incoming request.
     *
     * @param  \Illuminate\Http\Request $request
     * @param  \Closure $next
     * @return mixed
     */
    public function handle($request, Closure $next)
    {
        $reCaptchaResponse = $request->get('g-recaptcha-response');

        //Request does not have recaptcha response
        if (empty($reCaptchaResponse)) {
            throw new Exception('No ReCaptcha response given');
        }

        //Verify response
        try {
            $response = $this->reCaptcha->verify($reCaptchaResponse, $request->ip());
        } catch (Exception $e) {
            return $this->json($e->getMessage(), 400);
        }

        if (!$response->isSuccess()) {
            return $this->json('ReCaptcha verification failed', 422);
        }

        return $next($request);
    }

    /**
     * Return a new JSON response from the application.
     *
     * @param  string|array  $data
     * @param  int    $status
     * @param  array  $headers
     * @param  int    $options
     * @return \Illuminate\Http\JsonResponse;
     */
    protected function json($data = [], $status = 200, array $headers = [], $options = 0)
    {
        return new JsonResponse($data, $status, $headers, $options);
    }
}
