<?php

namespace Tests;

use CloudCreativity\LaravelJsonApi\Testing\MakesJsonApiRequests;
use CloudCreativity\LaravelJsonApi\Testing\TestResponse;
use Illuminate\Foundation\Testing\TestCase as BaseTestCase;

abstract class TestCase extends BaseTestCase
{
    use CreatesApplication, MakesJsonApiRequests;


    /**
     * Tests that request was rejected either by HTTP status 403, 404 or 405
     *
     * @param TestResponse $response
     */
    public function assertApiRejection(TestResponse $response) {
        $this->assertContains($response->getStatusCode(), [403, 404, 405], 'Response status must be either 403 Forbidden, 404 Not Found or 405 Method Not Allowed');
        $response->assertErrors($response->getStatusCode(), [[]]);
    }
}
