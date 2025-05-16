<?php

declare(strict_types=1);

use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;

uses(RefreshDatabase::class);

it('filters users by search term', function () {
    // Create users with specific names and emails
    User::factory()->create([
        'name' => 'John Doe',
        'email' => 'john@example.com',
    ]);

    User::factory()->create([
        'name' => 'Jane Smith',
        'email' => 'jane@example.com',
    ]);

    User::factory()->create([
        'name' => 'Bob Johnson',
        'email' => 'bob@example.com',
    ]);

    // Test filtering by name
    $filteredByName = User::filter(['search' => 'John'])->get();
    expect($filteredByName)->toHaveCount(1);
    expect($filteredByName->first()->name)->toBe('John Doe');

    // Test filtering by email
    $filteredByEmail = User::filter(['search' => 'jane'])->get();
    expect($filteredByEmail)->toHaveCount(1);
    expect($filteredByEmail->first()->email)->toBe('jane@example.com');
});

it('filters users by role', function () {
    // Create users with different roles
    User::factory()->create([
        'name' => 'Admin User',
        'role' => 'admin',
    ]);

    User::factory()->create([
        'name' => 'Regular User',
        'role' => 'user',
    ]);

    User::factory()->create([
        'name' => 'Suspended User',
        'role' => 'suspended',
    ]);

    // Test filtering by role
    $admins = User::filter(['role' => 'admin'])->get();
    expect($admins)->toHaveCount(1);
    expect($admins->first()->role)->toBe('admin');

    $regularUsers = User::filter(['role' => 'user'])->get();
    expect($regularUsers)->toHaveCount(1);
    expect($regularUsers->first()->role)->toBe('user');

    $suspendedUsers = User::filter(['role' => 'suspended'])->get();
    expect($suspendedUsers)->toHaveCount(1);
    expect($suspendedUsers->first()->role)->toBe('suspended');
});
