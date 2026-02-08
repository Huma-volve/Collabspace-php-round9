<?php

namespace Tests\Feature;

use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class ExampleTest extends TestCase
{
    use RefreshDatabase;

    protected function setUp(): void
    {
        parent::setUp();

        $this->seed();
    }

    public function test_user_count_is_zero()
    {
        $this->assertEquals(3, \App\Models\User::count());
    }

    public function testApplicationReturnsSuccessfulResponse()
    {
        $response = $this->get('/');

        $response->assertStatus(200);
    }
}
