<?php

declare(strict_types=1);

use App\Models\Listing;
use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Support\Facades\Gate;

uses(RefreshDatabase::class);

beforeEach(function () {
    $this->user = User::factory()->create(['role' => 'user']);
    $this->admin = User::factory()->create(['role' => 'admin']);
    $this->listing = Listing::factory()->create(['approved' => false]);
});

test('admin can view dashboard', function () {
    $response = $this->actingAs($this->admin)
        ->get(route('admin.index'));

    $response->assertOk();
});

test('non admin cannot access dashboard', function () {
    $response = $this->actingAs($this->user)
        ->get(route('admin.index'));

    $response->assertForbidden();
});

test('admin can view user details', function () {
    $response = $this->actingAs($this->admin)
        ->get(route('user.show', $this->user));

    $response->assertOk()
        ->assertInertia(fn ($page) => $page
            ->component('Admin/User')
            ->has('user')
            ->has('listings')
            ->has('status')
        );
});

test('admin can update user role', function () {
    Gate::define('modifyRole', fn () => true);

    $response = $this->actingAs($this->admin)
        ->put(route('admin.role', $this->user), [
            'role' => 'moderator',
        ]);

    $response->assertRedirect(route('admin.index'))
        ->assertSessionHas('status', 'User role changed to moderator successfully.');

    expect($this->user->fresh()->role)->toBe('moderator');
});

test('admin cannot update user role with invalid data', function () {
    Gate::define('modifyRole', fn () => true);

    $response = $this->actingAs($this->admin)
        ->put(route('admin.role', $this->user), [
            'role' => '',
        ]);

    $response->assertInvalid(['role']);
});

test('non admin cannot update user role', function () {
    Gate::define('modifyRole', fn () => false);

    $response = $this->actingAs($this->user)
        ->put(route('admin.role', $this->admin), [
            'role' => 'user',
        ]);

    $response->assertForbidden();
});

test('admin can toggle listing approval', function () {
    Gate::define('approve', fn () => true);

    $listing = Listing::factory()->create(['approved' => 0]);

    $response = $this->actingAs($this->admin)
        ->put(route('listing.toggleApproval', $listing));

    $response->assertRedirect()
        ->assertSessionHas('status', 'Listing status changed successfully.');

    expect($listing->fresh()->approved)->toBe(1);

    // Test toggling back to false
    $response = $this->actingAs($this->admin)
        ->put(route('listing.toggleApproval', $listing));

    $response->assertRedirect()
        ->assertSessionHas('status', 'Listing status changed successfully.');

    expect($listing->fresh()->approved)->toBe(0);
});

test('non admin cannot toggle listing approval', function () {
    Gate::define('approve', fn () => false);

    $listing = Listing::factory()->create(['approved' => 0]);

    $response = $this->actingAs($this->user)
        ->put(route('listing.toggleApproval', $listing));

    $response->assertForbidden();
    expect($listing->fresh()->approved)->toBe(0);
});
