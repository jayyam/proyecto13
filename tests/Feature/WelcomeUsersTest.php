<?php

namespace Tests\Feature;

use Tests\TestCase;
use Illuminate\Foundation\Testing\WithFaker;
use Illuminate\Foundation\Testing\RefreshDatabase;

class WelcomeUsersTest extends TestCase
{
    /** @test */
    function it_welcomes_users_with_nicknames()
    {
        $this->get('saludo/omar/moreno')
        ->assertStatus(200)
        ->assertSee("Bienvenido Omar. Tu apodo es moreno.");
    }

    /** @test */
    function it_welcomes_users_without_nicknames()
    {
        $this->get('saludo/omar')
            ->assertStatus(200)
            ->assertSee("Bienvenido Omar.");
    }
}
