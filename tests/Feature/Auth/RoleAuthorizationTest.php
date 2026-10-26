<?php

use App\Models\User;
use Database\Seeders\AdminUserSeeder;

test('guests are redirected from the admin dashboard', function () {
    $this->get('/admin/dashboard')->assertRedirect('/login');
});

test('regular users receive a forbidden response from the admin dashboard', function () {
    $user = User::factory()->create();

    $this->actingAs($user)
        ->get('/admin/dashboard')
        ->assertForbidden()
        ->assertSee('Akses Ditolak');
});

test('admins can access the admin dashboard', function () {
    $admin = User::factory()->admin()->create();

    $this->actingAs($admin)
        ->get('/admin/dashboard')
        ->assertOk()
        ->assertSee('Statistik Pengguna');
});

test('authenticated users can access the user dashboard', function () {
    $user = User::factory()->create();

    $this->actingAs($user)
        ->get('/user/dashboard')
        ->assertOk();
});

test('the dashboard route redirects users based on their role', function () {
    $admin = User::factory()->admin()->create();
    $user = User::factory()->create();

    $this->actingAs($admin)
        ->get('/dashboard')
        ->assertRedirect(route('admin.dashboard'));

    $this->actingAs($user)
        ->get('/dashboard')
        ->assertRedirect(route('user.dashboard'));
});

test('new registrations receive the regular user role', function () {
    $response = $this->post('/register', [
        'name' => 'New User',
        'email' => 'new@example.com',
        'password' => 'password',
        'password_confirmation' => 'password',
    ]);

    $response->assertRedirect(route('dashboard', absolute: false));

    expect(User::where('email', 'new@example.com')->first()->role)
        ->toBe(User::ROLE_USER);
});

test('the seeder creates an admin and a regular user', function () {
    $this->seed(AdminUserSeeder::class);

    expect(User::where('email', 'admin@example.com')->first()->isAdmin())->toBeTrue()
        ->and(User::where('email', 'user@example.com')->first()->isUser())->toBeTrue();
});
