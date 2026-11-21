<?php

namespace Tests\Feature;

use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class LandingPageTest extends TestCase
{
    use RefreshDatabase;

    public function test_home_page_renders_landing(): void
    {
        $this->get('/')
            ->assertOk()
            ->assertSee('Wakafine')
            ->assertSee('Safe ride.', false)
            ->assertSee(route('admin.login'));
    }
}
