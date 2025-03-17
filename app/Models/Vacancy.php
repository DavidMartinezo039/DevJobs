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
        return $this->belongsToMany(User::class, 'candidates')->withPivot('cv')->withTimestamps()->orderByPivot('created_at', 'DESC');
    }

    public function recruiter(): BelongsTo
    {
        return $this->belongsTo(User::class, 'user_id');
    }
}
