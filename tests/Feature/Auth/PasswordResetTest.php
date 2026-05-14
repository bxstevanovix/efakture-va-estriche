<?php

namespace Tests\Feature\Auth;

use Illuminate\Support\Facades\Notification;
use Tests\TestCase;

class PasswordResetTest extends TestCase
{
    public function test_reset_password_link_screen_is_forbidden(): void
    {
        $response = $this->get('/forgot-password');

        $response->assertForbidden();
    }

    public function test_reset_password_link_request_is_forbidden(): void
    {
        Notification::fake();

        $response = $this->post('/forgot-password', ['email' => 'user@example.com']);

        $response->assertForbidden();
        Notification::assertNothingSent();
    }

    public function test_reset_password_screen_is_forbidden(): void
    {
        $response = $this->get('/reset-password/test-token');

        $response->assertForbidden();
    }

    public function test_password_reset_submission_is_forbidden(): void
    {
        $response = $this->post('/reset-password', [
            'token' => 'test-token',
            'email' => 'user@example.com',
            'password' => 'password',
            'password_confirmation' => 'password',
        ]);

        $response->assertForbidden();
    }
}
