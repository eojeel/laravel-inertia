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
        'title' => $data['title'],
        'description' => $data['description'],
        'tags' => $data['tags'],
        'link' => $data['link'],
    ]);

    Storage::disk('s3')->assertExists('images/listing/'.$file->hashName());
});

it('can update listing but keep image', function () {
    $file = UploadedFile::fake()->image('new-listing.jpg');
    $data = [
        'title' => 'Updated Title',
        'description' => 'Updated Description',
        'tags' => 'updated,listing',
        'link' => 'https://updated-example.com',
        'image' => '',
    ];

    actingAs($this->user)
        ->put(route('listing.update', $this->listing), $data)
        ->assertRedirect();


    $this->assertDatabaseHas('listings', [
        'id' => $this->listing->id,
        'title' => $data['title'],
        'description' => $data['description'],
        'tags' => $data['tags'],
        'link' => $data['link'],
        'image' =>  $this->listing->image,
    ]);

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

it('cannot view listing for suspended user', function () {

    actingAs($this->suspendedUser)
        ->get(route('listing.create'))
        ->assertRedirect('/dashboard');
});

it('admin can toggle listing approval', function () {
    $admin = User::factory()->create([
        'email_verified_at' => now(),
        'role' => 'admin'
    ]);

    $listing = Listing::factory()->create([
        'approved' => false
    ]);

    actingAs($admin)
        ->put(route('listing.toggleApproval', $listing))
        ->assertRedirect();

    $this->assertDatabaseHas('listings', [
        'id' => $listing->id,
        'approved' => true
    ]);
});

it('non-admin cannot toggle listing approval', function () {
    $regularUser = User::factory()->create([
        'email_verified_at' => now(),
        'role' => 'user'
    ]);

    $listing = Listing::factory()->create([
        'approved' => false
    ]);

    actingAs($regularUser)
        ->put(route('listing.toggleApproval', $listing))
        ->assertStatus(403);

    $this->assertDatabaseHas('listings', [
        'id' => $listing->id,
        'approved' => false
    ]);
});

it('directly tests the approve policy method', function () {
    $admin = User::factory()->create([
        'email_verified_at' => now(),
        'role' => 'admin'
    ]);

    $regularUser = User::factory()->create([
        'email_verified_at' => now(),
        'role' => 'user'
    ]);

    $policy = new \App\Policies\ListingPolicy();

    // Test with admin user - should return true
    expect($policy->approve($admin))->toBeTrue();

    // Test with regular user - should return false
    expect($policy->approve($regularUser))->toBeFalse();
});

it('filters listings by user_id', function () {
    // Create listings for different users
    $user1 = User::factory()->create(['role' => 'user']);
    $user2 = User::factory()->create(['role' => 'user']);

    $listing1 = Listing::factory()->create([
        'user_id' => $user1->id,
        'approved' => true
    ]);

    $listing2 = Listing::factory()->create([
        'user_id' => $user2->id,
        'approved' => true
    ]);

    // Test filtering by user_id
    $filteredListings = Listing::filter(['user_id' => $user1->id])->get();

    expect($filteredListings)->toHaveCount(1);
    expect($filteredListings->first()->user_id)->toBe($user1->id);
});

it('filters listings by tag', function () {
    // Create listings with different tags
    $listing1 = Listing::factory()->create([
        'tags' => 'php,laravel,coding',
        'approved' => true
    ]);

    $listing2 = Listing::factory()->create([
        'tags' => 'vue,javascript,frontend',
        'approved' => true
    ]);

    // Test filtering by tag
    $filteredByPhp = Listing::filter(['tag' => 'php'])->get();
    expect($filteredByPhp)->toHaveCount(1);
    expect($filteredByPhp->first()->id)->toBe($listing1->id);

    $filteredByVue = Listing::filter(['tag' => 'vue'])->get();
    expect($filteredByVue)->toHaveCount(1);
    expect($filteredByVue->first()->id)->toBe($listing2->id);
});

it('filters listings by approval status', function () {
    // Create listings with different approval statuses
    $approvedListing = Listing::factory()->create([
        'approved' => true
    ]);

    $unapprovedListing = Listing::factory()->create([
        'approved' => false
    ]);

    // Test filtering by approved status
    $filteredApproved = Listing::filter(['approved' => true])->get();
    $filteredUnapproved = Listing::filter(['approved' => false])->get();

    // Check that the approved filter works
    expect($filteredApproved->contains('id', $approvedListing->id))->toBeTrue();
    expect($filteredApproved->contains('id', $unapprovedListing->id))->toBeFalse();

    // Check that the unapproved filter works
    expect($filteredUnapproved->contains('id', $unapprovedListing->id))->toBeTrue();
    expect($filteredUnapproved->contains('id', $approvedListing->id))->toBeFalse();
});
