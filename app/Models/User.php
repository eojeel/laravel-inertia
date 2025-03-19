<?php

declare(strict_types=1);

namespace App\Models;

use Illuminate\Contracts\Auth\MustVerifyEmail;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;

final class User extends Authenticatable implements MustVerifyEmail
{
    use HasFactory;
    use Notifiable;

    /**
     * The attributes that are mass assignable.
     *
     * @var array<int, string>
     */
    protected $fillable = [
        'name',
        'email',
        'password',
    ];

    /**
     * The attributes that should be hidden for serialization.
     *
     * @var array<int, string>
     */
    protected $hidden = [
        'password',
        'remember_token',
    ];

    public function listing(): HasMany
    {
        return $this->hasMany(Listing::class);
    }

    public function isNotSuspended(): bool
    {
        return $this->role !== 'suspended';
    }

    public function isAdmin(): bool
    {
        return $this->role === 'admin';
    }

    /**
     * Filter the query based on the given search query.
     * @param  Builder<Listing>  $query
     * @param  array<string, string>  $filters
     */
    public function scopeFilter(Builder $query, array $filter): void
    {
        if ($filter['search'] ?? false) {
            $query->where(fn (Builder $query) => $query->Where('name', 'like', '%'.$filter['search'].'%')
                ->orWhere('email', 'like', '%'.$filter['search'].'%'));
        }

        if ($filter['role'] ?? false) {
            $query->where('role', $filter['role']);
        }
    }

    /**
     * Get the attributes that should be cast.
     *
     * @return array<string, string>
     */
    protected function casts(): array
    {
        return [
            'email_verified_at' => 'datetime',
            'password' => 'hashed',
        ];
    }
}
