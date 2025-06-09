<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsToMany;
use Illuminate\Database\Eloquent\SoftDeletes;

class DigitalSkill extends Model
{
    use HasFactory, SoftDeletes;

    protected $fillable = ['name'];

    public function scopeVisibleFor($query, $user)
    {
        if ($user->hasRole('god')) {
            return $query->withTrashed();
        }

        return $query;
    }

    public function scopeOrderedByName($query)
    {
        return $query->orderBy('name');
    }

    public function cvs(): BelongsToMany
    {
        return $this->belongsToMany(CV::class, 'cvs_digital_skills', 'digital_skill_id', 'cv_id')->withTimestamps();
    }
}
