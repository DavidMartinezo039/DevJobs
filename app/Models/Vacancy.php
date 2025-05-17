<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\BelongsToMany;
use Illuminate\Database\Eloquent\Relations\HasMany;

class Vacancy extends Model
{
    use HasFactory;

    protected $casts = [
        'last_day' => 'date',
    ];

    protected $fillable = [
        'title',
        'salary_id',
        'category_id',
        'user_id',
        'company',
        'last_day',
        'description',
        'image',
    ];

    public function scopeVacanciesByRol($query)
    {
        if (auth()->user()->hasRole('god')) {
            return $query;
        } elseif (auth()->user()->hasRole('moderator')) {
            $recruiterIds = User::role('recruiter')->pluck('id');
            return $query->whereIn('user_id', [auth()->id(), ...$recruiterIds]);
        } else {
            return $query->where('user_id', auth()->id());
        }
    }

    public function scopeHomeVacancies($query, $filters)
    {
        return $query
            ->when($filters['term'] ?? null, function ($query, $term) {
                $query->where(function ($q) use ($term) {
                    $q->where('title', 'LIKE', '%' . $term . '%')
                        ->orWhere('company', 'LIKE', '%' . $term . '%');
                });
            })
            ->when($filters['category'] ?? null, function ($query, $category) {
                $query->where('category_id', $category);
            })
            ->when($filters['salary'] ?? null, function ($query, $salary) {
                $query->where('salary_id', $salary);
            })
            ->where('last_day', '>=', today());
    }

    public function salary(): BelongsTo
    {
        return $this->belongsTo(Salary::class);
    }

    public function category(): BelongsTo
    {
        return $this->belongsTo(Category::class);
    }

    public function users(): BelongsToMany
    {
        return $this->belongsToMany(User::class, 'candidates')->withPivot('cv', 'status')->withTimestamps()->orderByPivot('created_at', 'DESC');
    }

    public function user()
    {
        return $this->belongsTo(User::class);
    }

    public function recruiter(): BelongsTo
    {
        return $this->belongsTo(User::class, 'user_id');
    }
}
