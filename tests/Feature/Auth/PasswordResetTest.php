<?php

use App\Models\User;
use Illuminate\Support\Facades\DB;

test('reset password link screen can be rendered', function () {
    $response = $this->get('/forgot-password');

    $response->assertStatus(200);
});

test('reset password link can be requested', function () {
    $user = User::factory()->create();

    $response = $this->post('/forgot-password', ['email' => $user->email]);

    $response->assertSessionHas('status');

    $this->assertDatabaseHas('password_reset_tokens', [
        'email' => $user->email,
    ]);
});

test('reset password screen can be rendered', function () {
    $user = User::factory()->create();

    $this->post('/forgot-password', ['email' => $user->email]);

    $token = DB::table('password_reset_tokens')
        ->where('email', $user->email)
        ->first();

    $this->assertNotNull($token);

    $response = $this->get('/reset-password/test-token?email='.$user->email);

    $response->assertStatus(200)
        ->assertSee(route('password.update'));
});

test('password can be reset with valid token', function () {
    $user = User::factory()->create();

    // Create token manually (same way the controller does)
    $plainToken = 'test-reset-token-123';

    DB::table('password_reset_tokens')->updateOrInsert(
        ['email' => $user->email],
        [
            'token' => hash('sha256', $plainToken),
            'created_at' => now(),
        ]
    );

    $response = $this->post('/reset-password', [
        'token' => $plainToken,
        'email' => $user->email,
        'password' => 'new-password',
        'password_confirmation' => 'new-password',
    ]);

    $response
        ->assertSessionHasNoErrors()
        ->assertRedirect(route('login'));
});
