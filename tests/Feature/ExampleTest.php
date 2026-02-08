<?php

use Tests\TestCase;

class ExampleTest extends TestCase
{
    public function testApplicationReturnsSuccessfulResponse()
    {
        $response = $this->get('/');

        $response->assertStatus(200);
    }
}
