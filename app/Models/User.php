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
        return match (true) {
            $this->hasRole('god') => self::with('vacancies')->get()->pluck('vacancies')->flatten(),

            $this->hasRole('moderator') => self::whereHas('roles', fn($q) => $q->where('name', 'developer'))
                ->orWhere('id', $this->id)
                ->with('vacancies')
                ->get()
                ->pluck('vacancies')
                ->flatten(),

            default => $this->vacancies,
        };
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
