<?php

declare(strict_types=1);

namespace App\Http\Controllers;

use App\Http\Middleware\NotSuspended;
use App\Http\Requests\ListingCreate;
use App\Models\Listing;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Routing\Controllers\HasMiddleware;
use Illuminate\Routing\Controllers\Middleware;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Gate;
use Illuminate\Support\Facades\Storage;
use Inertia\Inertia;
use Inertia\Response;

final class ListingController extends Controller implements HasMiddleware
{
    public static function Middleware(): array
    {
        return [
            new Middleware(
                ['auth', 'verified', NotSuspended::class],
                except: ['index', 'show']
            ),
        ];
    }

    /**
     * Display a listing of the resource.
     */
    public function index(Request $request): Response
    {
        $listings = Listing::with('user')
            ->whereHas('user', static fn (Builder $q) => $q->where('role', '!=', 'suspended'))
            ->where('approved', true)
            ->filter(request(['search', 'user_id', 'tag']))
            ->latest()
            ->paginate(10)
            ->withQueryString();

        // search
        return Inertia::render('Home', [
            'listings' => $listings,
            'searchTerm' => $request->search,
        ]);
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create(): Response
    {
        Gate::authorize('create', Listing::class);

        return Inertia::render('Listing/Create', [
            'image' => Storage::url('images/default.jpg'),
        ]);
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(ListingCreate $request): RedirectResponse
    {
        Gate::authorize('create', Listing::class);

        $attributes = $request->validated();

        if ($attributes['image']) {
            $attributes['image'] = Storage::disk('s3')->putFile('images/listing', $attributes['image']);
        }

        $listing = $request->user()->listing()->create($attributes);

        return redirect()->route('listing.show', $listing)
            ->with('success', 'Listing created successfully');
    }

    /**
     * Display the specified resource.
     */
    public function show(Listing $listing): Response
    {
        Gate::authorize('view', $listing);

        return Inertia::render('Listing/Show', [
            'listing' => $listing,
            'user' => $listing->user->only('id', 'name', 'email'),
            'canModify' => Auth::User()?->can('modify', $listing) ?? false,
        ]);
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(Listing $listing): Response
    {
        Gate::authorize('modify', $listing);

        return Inertia::render('Listing/Edit', [
            'listing' => $listing,
            'image' => Storage::url('images/default.jpg'),
        ]);
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(ListingCreate $request, Listing $listing): RedirectResponse
    {
        Gate::authorize('modify', $listing);

        $attributes = $request->validated();

        if ($attributes['image'] && $listing->image) {
            Storage::disk('s3')->delete($listing->image);
            $attributes['image'] = Storage::disk('s3')->putFile('images/listing', $attributes['image']);
        } else {
            $attributes['image'] = parse_url($listing->image, PHP_URL_PATH);
        }

        $listing->update($attributes);

        return redirect()->route('listing.show', $listing)
            ->with('success', 'Listing updated successfully');
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(Listing $listing): RedirectResponse
    {
        Gate::authorize('modify', $listing);

        if (parse_url($listing->image, PHP_URL_PATH) !== 'images/default.jpg') {
            Storage::disk('s3')->delete($listing->image);
        }

        $listing->delete();

        return redirect()->route('home')
            ->with('success', 'Listing created successfully');

    }
}
