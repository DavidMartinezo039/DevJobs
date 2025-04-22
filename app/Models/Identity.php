<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsToMany;

class Identity extends Model
{
    use HasFactory;

    protected $fillable = ['type'];

    public function personalData(): BelongsToMany
    {
        return $this->belongsToMany(PersonalData::class, 'identity_personal_data')
            ->withPivot('number')->withTimestamps();
    }
}
