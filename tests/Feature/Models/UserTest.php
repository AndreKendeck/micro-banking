<?php

namespace Tests\Feature\Models;

use App\Models\User;
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
    public function user_are_added_to_the_application_with_respective_accounts_and_transactions()
    {
        User::all()->each(function (User $user) {
            $this->assertTrue($user->accounts->count() >= 2);
            $this->assertTrue($user->accounts->count() <= 5);
        });
    }
}
