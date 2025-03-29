<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsToMany;

class SocialMedia extends Model
{
    use HasFactory;

    protected $fillable = ['type'];

    public function personalData(): BelongsToMany
    {
        return $this->belongsToMany(PersonalData::class, 'personal_data_social_media')
            ->withPivot('user_name', 'url');
    }
}
