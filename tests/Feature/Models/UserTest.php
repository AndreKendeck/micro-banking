<?php

namespace Tests\Feature\Models;

use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Foundation\Testing\WithFaker;
use Tests\TestCase;

class UserTest extends TestCase
{

    /** @test */
    public function the_default_user_is_set_in_the_application()
    {
        $this->assertDatabaseHas('users', [
            'name' => 'admin',
            'email' => 'admin@mail.test'
        ]);
    }


    /** @test */
    public function users_are_seeded_into_the_application()
    {
        User::all()->each(function (User $user) {
            $this->assertTrue($user->accounts->count() >= 2, "The user accounts count for {$user->name} is {$user->accounts->count()}");
            $this->assertTrue($user->accounts->count() <= 5, "The user accounts count is {$user->accounts->count()}");
        });
    }
}
