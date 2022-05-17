<?php

namespace Tests\Feature;

use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Foundation\Testing\WithFaker;
use Tests\TestCase;

class UserAuthenticationTest extends TestCase
{
    use RefreshDatabase;
    
    public function testAccessIfUserLoginExist()
    {
        $response = $this->get(route('user.login'));
        $response->assertViewIs('user.login');
    }

    public function testAuthenticateUserIfCredentialsAreCorrect()
    {
        $user = User::factory()->create();

        $response = $this->post(route('user.authenticate'), [
            'username' => $user->username,
            'password' => '1234'
        ]);

        $response->assertRedirect(route('user.dashboard'));
        $this->assertAuthenticated();
    }

    public function testAuthenticateUserUsingEmail()
    {
        $user = User::factory()->create();

        $response = $this->post(route('user.authenticate', [
            'username' => $user->email,
            'password' => '1234'
        ]));

        $response->assertRedirect(route('user.dashboard'));
        $this->assertAuthenticated();
    }

    public function testUsernameFieldDoesntExistInRequestThenFailSinceUsernameIsRequired()
    {
        $user= User::factory()->create();

        $response = $this->post(route('user.authenticate', [
            'password' => '1234'
        ]));

        $response->assertRedirect(route('user.login'));
        $response->assertSessionHasErrors(['username']);
        $this->assertGuest();
    }

    public function testPasswordFieldDoesntExistInRequestThenFailSincePasswordIsRequired()
    {
        $user= User::factory()->create();

        $response = $this->post(route('user.authenticate', [
            'username' => $user->email
        ]));

        $response->assertRedirect(route('user.login'));
        $response->assertSessionHasErrors(['password']);
        $this->assertGuest();
    }

    public function testAuthenticateUserWithInvalidCredentialThenFailSinceUserDoesntExistInDatabase()
    {
        $response = $this->post(route('user.authenticate'), [
            'username' => '1234',
            'password' => '1234'
        ]);

        $response->assertRedirect(route('user.login'));
        $response->assertSessionHasErrors(['username']);
        $this->assertGuest();
    }

    public function testUnverifiedAccountCannotLoginEvenIfAccountExistOnDatabase()
    {
        $user = User::factory()->create([
            'email_verified_at' => null
        ]);

        $response = $this->post(route('user.authenticate'), [
            'username' => $user->username,
            'password' => '1234'
        ]);

        $response->assertRedirect(route('user.login'));
        $response->assertSessionHasErrors(['username']);
        $this->assertGuest();
    }
}
