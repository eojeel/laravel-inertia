<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class Listing extends Model
{
    /** @use HasFactory<\Database\Factories\ListingFactory> */
    use HasFactory;

    protected $fillable = [
        'title',
        'desc',
        'tags',
        'emails',
        'link',
        'image',
        'approved',
    ];

    public function user(): BelongsTo
    {
        return $this->belongsTo(User::class);
    }

    /**
     * Filter the query based on the given search query.
     */
    public function scopeFilter(Builder $query, array $filters): void
    {
        if (!empty($filters['search'])) {
            $query
                ->where('title', 'like', '%'.request('search').'%')
                ->orWhere('desc', 'like', '%'.request('search').'%');
        }
    }
}
