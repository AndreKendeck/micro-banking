<?php

namespace Tests\Feature;

use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Foundation\Testing\WithFaker;
use Tests\TestCase;

class LoginTest extends TestCase
{

    /** @test */
    public function email_and_password_is_required_on_login()
    {
        $this->postJson(route('login'), [])
            ->assertJsonValidationErrors(['email', 'password'])
            ->assertUnprocessable();
    }
}
