<?php

namespace Tests\Feature;

use Tests\TestCase;

class SmokeTest extends TestCase
{
    public function test_root_redirects(): void
    {
        $response = $this->get('/');

        $response->assertRedirect('/login');
    }
}
