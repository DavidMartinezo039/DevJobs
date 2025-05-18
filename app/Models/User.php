<?php

namespace App\Models;

use Illuminate\Contracts\Auth\MustVerifyEmail;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Relations\BelongsToMany;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;
use Laravel\Sanctum\HasApiTokens;
use Spatie\Permission\Traits\HasRoles;
use Illuminate\Pagination\LengthAwarePaginator;
use Illuminate\Support\Collection;

class User extends Authenticatable implements MustVerifyEmail
{
    /** @use HasFactory<\Database\Factories\UserFactory> */
    use HasFactory, Notifiable, HasRoles, HasApiTokens;

    /**
     * The attributes that are mass assignable.
     *
     * @var list<string>
     */
    protected $fillable = [
        'name',
        'email',
        'password',
    ];

    /**
     * The attributes that should be hidden for serialization.
     *
     * @var list<string>
     */
    protected $hidden = [
        'password',
        'remember_token',
    ];

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


    public function getAccessibleApplications()
    {
        $applications = match (true) {
            $this->hasRole('god') => self::with('vacancies')->get()->pluck('vacancies')->flatten(),

            $this->hasRole('moderator') => self::whereHas('roles', fn($q) => $q->where('name', 'developer'))
                ->orWhere('id', $this->id)
                ->with('vacancies')
                ->get()
                ->pluck('vacancies')
                ->flatten(),

            default => $this->vacancies,
        };

        return $this->paginateCollection($applications);
    }

    protected function paginateCollection(Collection $items, int $perPage = 10, string $pageName = 'page')
    {
        $currentPage = LengthAwarePaginator::resolveCurrentPage($pageName);
        $itemsPaged = $items->forPage($currentPage, $perPage);

        return new LengthAwarePaginator(
            $itemsPaged,
            $items->count(),
            $perPage,
            $currentPage,
            ['path' => request()->url(), 'query' => request()->query()]
        );
    }


    public function vacancies(): BelongsToMany
    {
        return $this->belongsToMany(Vacancy::class, 'candidates')->withPivot('cv', 'status')->withTimestamps();
    }

    public function myVancacies(): HasMany
    {
        return $this->hasMany(Vacancy::class);
    }
}
