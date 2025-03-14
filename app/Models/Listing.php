<?php

declare(strict_types=1);

namespace App\Models;

use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Support\Facades\Storage;

final class Listing extends Model
{
    /** @use HasFactory<\Database\Factories\ListingFactory> */
    use HasFactory;

    protected $fillable = [
        'title',
        'description',
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
        if (! empty($filters['search'])) {
            $query->where(function ($q): void {
                $q->where('title', 'like', '%'.request('search').'%')
                    ->orWhere('description', 'like', '%'.request('search').'%');
            });
        }

        if (! empty($filters['user_id'])) {
            $query->where('user_id', request('user_id'));
        }

        if (! empty($filters['tag'])) {
            $query->where('tags', 'like', '%'.request('tag').'%');
        }

        if (! empty($filters['approved'])) {
            $query->where('approved', ! $filters['approved']);
        }
    }

    /**
     * Get the image attribute.
     */
    public function getImageAttribute(?string $value): ?string
    {
        $disk = Storage::disk('s3');
        if ($value && $disk->exists($value)) {
            return $disk->url($value);
        }

        return $disk->url('images/default.jpg');
    }
}
