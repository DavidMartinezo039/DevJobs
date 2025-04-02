<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsToMany;

class Language extends Model
{
    use HasFactory;

    protected $fillable = ['name'];

    public function cvs(): BelongsToMany
    {
        return $this->belongsToMany(CV::class, 'cvs_languages', 'language_id', 'cv_id')->withPivot('level')->withTimestamps();
    }
}
