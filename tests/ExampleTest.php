<?php

use PHPUnit\Framework\TestCase;
use Helpers\Response;

class ExampleTest extends TestCase
{
    public function testJsonResponse()
    {
        $response = Response::json(['message' => 'Test'], 200, true);
        $this->assertJson($response);
        $this->assertStringContainsString('"message":"Test"', $response);
    }
}
