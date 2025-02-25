<?php

namespace App\Http\Controllers;

use App\Http\Requests\ListingCreate;
use App\Models\Listing;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;
use Inertia\Inertia;
use Inertia\Response;

class ListingController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index(Request $request): Response
    {
        $listings = Listing::whereHas('user', static fn (Builder $q) => $q->where('role', '!=', 'suspended'))
            ->with('user')
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
        return Inertia::render('Listing/Create', [
            'image' => Storage::url('images/default.jpg'),
        ]);
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(ListingCreate $request): RedirectResponse
    {
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
        return inertia::render('Listing/Show', [
            'listing' => $listing,
            'user' => $listing->user->only('id', 'name', 'email'),
        ]);
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(Listing $listing): void
    {
        //
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, Listing $listing): void
    {
        //
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(Listing $listing): void
    {
        //
    }
}
