<?php

declare(strict_types=1);

use App\Models\Listing;
use App\Models\User;
use Illuminate\Http\UploadedFile;
use Illuminate\Support\Facades\Storage;
use Inertia\Testing\AssertableInertia;
use function Pest\Laravel\{get, post, put, delete};
use function Pest\Laravel\actingAs;

beforeEach(function () {
    $this->user = User::factory()->create([
        'email_verified_at' => now(),
        'role' => 'user'
    ]);

    $this->suspendedUser = User::factory()->create([
        'role' => 'suspended',
        'email_verified_at' => now()
    ]);

    $this->listing = Listing::factory()->create([
        'user_id' => $this->user->id,
        'approved' => true
    ]);

    Storage::fake('s3');
});

it('can load listing page', function () {
    get(route('home'))
        ->assertOk()
        ->assertInertia(fn (AssertableInertia $page) => $page
            ->component('Home')
            ->has('listings')
            ->has('searchTerm')
            ->where('searchTerm', null)
        );
});

it('can filter listings by search term', function () {
    get(route('home', ['search' => 'test']))
        ->assertOk()
        ->assertInertia(fn (AssertableInertia $page) => $page
            ->component('Home')
            ->has('listings')
            ->where('searchTerm', 'test')
        );
});

it('cannot display listings from suspended users', function () {
    Listing::factory()->create(['user_id' => $this->suspendedUser->id]);

    get(route('home'))
        ->assertOk()
        ->assertInertia(fn (AssertableInertia $page) => $page
            ->component('Home')
            ->has('listings.data', 1)
            ->where('listings.data.0.user_id', $this->user->id)
        );
});

it('can display create form for authenticated user', function () {
    actingAs($this->user)
        ->get(route('listing.create'))
        ->assertOk()
        ->assertInertia(fn (AssertableInertia $page) => $page
            ->component('Listing/Create')
            ->has('image')
        );
});

it('cannot display create form for guest', function () {
    get(route('listing.create'))
        ->assertRedirect(route('login'));
});

it('can store new listing', function () {
    $file = UploadedFile::fake()->image('listing.jpg');
    $data = [
        'title' => 'Test Listing',
        'description' => 'Test Description',
        'tags' => 'test,listing',
        'link' => 'https://example.com',
        'image' => $file,
    ];

    actingAs($this->user)
        ->post(route('listing.store'), $data)
        ->assertRedirect();

    $this->assertDatabaseHas('listings', [
        'title' => 'Test Listing',
        'description' => 'Test Description',
        'tags' => 'test,listing',
        'link' => 'https://example.com',
        'user_id' => $this->user->id,
    ]);

    Storage::disk('s3')->assertExists('images/listing/'.$file->hashName());
});

it('validates listing creation', function () {
    actingAs($this->user)
        ->post(route('listing.store'), [])
        ->assertSessionHasErrors(['title', 'description']);
});

it('can display listing show page', function () {
    get(route('listing.show', $this->listing))
        ->assertOk()
        ->assertInertia(fn (AssertableInertia $page) => $page
            ->component('Listing/Show')
            ->has('listing')
            ->has('user')
            ->has('canModify')
            ->where('canModify', false)
        );
});

it('shows can modify true for owner', function () {
    actingAs($this->user)
        ->get(route('listing.show', $this->listing))
        ->assertOk()
        ->assertInertia(fn (AssertableInertia $page) => $page
            ->component('Listing/Show')
            ->where('canModify', true)
        );
});

it('can display edit form for owner', function () {
    actingAs($this->user)
        ->get(route('listing.edit', $this->listing))
        ->assertOk()
        ->assertInertia(fn (AssertableInertia $page) => $page
            ->component('Listing/Edit')
            ->has('listing')
            ->has('image')
        );
});

it('cannot display edit form for non owner', function () {
    $otherUser = User::factory()->create(['role' => 'user']);

    actingAs($otherUser)
        ->get(route('listing.edit', $this->listing))
        ->assertStatus(403);
});

it('can update listing', function () {
    $file = UploadedFile::fake()->image('new-listing.jpg');
    $data = [
        'title' => 'Updated Title',
        'description' => 'Updated Description',
        'tags' => 'updated,listing',
        'link' => 'https://updated-example.com',
        'image' => $file,
    ];

    actingAs($this->user)
        ->put(route('listing.update', $this->listing), $data)
        ->assertRedirect();

    $this->assertDatabaseHas('listings', [
        'id' => $this->listing->id,
        'title' => 'Updated Title',
        'description' => 'Updated Description',
        'tags' => 'updated,listing',
        'link' => 'https://updated-example.com',
    ]);

    Storage::disk('s3')->assertExists('images/listing/'.$file->hashName());
});

it('validates listing update', function () {
    actingAs($this->user)
        ->put(route('listing.update', $this->listing), [])
        ->assertSessionHasErrors(['title', 'description']);
});

it('cannot update listing for non owner', function () {
    $otherUser = User::factory()->create(['role' => 'user']);

    actingAs($otherUser)
        ->put(route('listing.update', $this->listing), [
            'title' => 'Updated Title',
            'description' => 'Updated Description',
        ])
        ->assertStatus(403);
});

it('can delete listing', function () {
    actingAs($this->user)
        ->delete(route('listing.destroy', $this->listing))
        ->assertRedirect();

    $this->assertDatabaseMissing('listings', ['id' => $this->listing->id]);
});

it('cannot delete listing for non owner', function () {
    $otherUser = User::factory()->create(['role' => 'user']);

    actingAs($otherUser)
        ->delete(route('listing.destroy', $this->listing))
        ->assertStatus(403);

    $this->assertDatabaseHas('listings', ['id' => $this->listing->id]);
});

it('deletes image from storage when listing is deleted', function () {
    Storage::fake('s3');

    $file = UploadedFile::fake()->image('listing.jpg');
    $path = Storage::disk('s3')->putFile('images/listing', $file);

    expect(Storage::disk('s3')->exists($path))->toBeTrue();

    $this->listing->forceFill([
        'image' => $path
    ])->save();

    actingAs($this->user)
        ->delete(route('listing.destroy', $this->listing));

    // The file should be deleted from storage
    expect(Storage::disk('s3')->exists($path))->toBeFalse();

    // The listing should be deleted from the database
    expect(Listing::find($this->listing->id))->toBeNull();
});
