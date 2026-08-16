<?php

use App\Models\User;

test('registration screen can be rendered', function () {
    $response = $this->get('/register');

    $response->assertStatus(200);
});

test('new users can register', function () {
    $response = $this->post('/register', [
        'name' => 'Test User',
        'email' => 'test@example.com',
        'password' => 'password',
        'password_confirmation' => 'password',
    ]);

    $this->assertAuthenticated();
    $response->assertRedirect(route('dashboard', absolute: false));
});

test('new users can register with phone number', function () {
    $response = $this->post('/register', [
        'name' => 'Test User Phone',
        'email' => 'testphone@example.com',
        'phone' => '09123456789',
        'password' => 'password',
        'password_confirmation' => 'password',
    ]);

    $this->assertAuthenticated();
    $this->assertDatabaseHas('users', [
        'email' => 'testphone@example.com',
        'phone' => '09123456789',
    ]);
    $response->assertRedirect(route('dashboard', absolute: false));
});

test('users cannot register with duplicate name', function () {
    User::factory()->create([
        'name' => 'Existing Name',
        'email' => 'user1@example.com',
    ]);

    $response = $this->post('/register', [
        'name' => 'Existing Name',
        'email' => 'user2@example.com',
        'password' => 'password123',
        'password_confirmation' => 'password123',
    ]);

    $response->assertSessionHasErrors(['name']);
});

test('users cannot register with duplicate phone number', function () {
    User::factory()->create([
        'name' => 'User One',
        'email' => 'u1@example.com',
        'phone' => '09998887777',
    ]);

    $response = $this->post('/register', [
        'name' => 'User Two',
        'email' => 'u2@example.com',
        'phone' => '09998887777',
        'password' => 'password123',
        'password_confirmation' => 'password123',
    ]);

    $response->assertSessionHasErrors(['phone']);
});
