<?php

use App\Models\User;

test('guests are redirected from the user management page', function () {
    $this->get('/admin/users')->assertRedirect('/login');
});

test('regular users cannot manage other users', function () {
    $user = User::factory()->create();
    $target = User::factory()->create();

    $this->actingAs($user)->get('/admin/users')->assertForbidden();
    $this->actingAs($user)
        ->patch(route('admin.users.update-role', $target), ['role' => User::ROLE_ADMIN])
        ->assertForbidden();
    $this->actingAs($user)
        ->delete(route('admin.users.destroy', $target))
        ->assertForbidden();
});

test('admins can see the user list', function () {
    $admin = User::factory()->admin()->create(['name' => 'Admin Satu']);
    $user = User::factory()->create(['name' => 'Budi']);

    $this->actingAs($admin)
        ->get('/admin/users')
        ->assertOk()
        ->assertSee('Admin Satu')
        ->assertSee('Budi')
        ->assertSee($user->email);
});

test('admins can change another user role', function () {
    $admin = User::factory()->admin()->create();
    $user = User::factory()->create();

    $this->actingAs($admin)
        ->patch(route('admin.users.update-role', $user), [
            'role' => User::ROLE_ADMIN,
        ])
        ->assertRedirect(route('admin.users.index'))
        ->assertSessionHas('status');

    expect($user->fresh()->role)->toBe(User::ROLE_ADMIN);
});

test('admins cannot change their own role', function () {
    $admin = User::factory()->admin()->create();

    $this->actingAs($admin)
        ->from(route('admin.users.index'))
        ->patch(route('admin.users.update-role', $admin), [
            'role' => User::ROLE_USER,
        ])
        ->assertRedirect(route('admin.users.index'))
        ->assertSessionHasErrors('role');

    expect($admin->fresh()->role)->toBe(User::ROLE_ADMIN);
});

test('the role must be a supported value', function () {
    $admin = User::factory()->admin()->create();
    $user = User::factory()->create();

    $this->actingAs($admin)
        ->patch(route('admin.users.update-role', $user), [
            'role' => 'superadmin',
        ])
        ->assertSessionHasErrors('role');

    expect($user->fresh()->role)->toBe(User::ROLE_USER);
});

test('admins can delete another user', function () {
    $admin = User::factory()->admin()->create();
    $user = User::factory()->create();

    $this->actingAs($admin)
        ->delete(route('admin.users.destroy', $user))
        ->assertRedirect(route('admin.users.index'))
        ->assertSessionHas('status');

    $this->assertModelMissing($user);
});

test('admins cannot delete their own account', function () {
    $admin = User::factory()->admin()->create();

    $this->actingAs($admin)
        ->from(route('admin.users.index'))
        ->delete(route('admin.users.destroy', $admin))
        ->assertRedirect(route('admin.users.index'))
        ->assertSessionHasErrors('user');

    $this->assertModelExists($admin);
});
