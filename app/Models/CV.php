<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\BelongsToMany;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Database\Eloquent\Relations\HasOne;

class CV extends Model
{
    use HasFactory;

    protected $table = 'cvs';

    protected $fillable = ['title', 'user_id'];

    public function user(): BelongsTo
    {
        return $this->belongsTo(User::class);
    }

    public function personalData(): HasOne
    {
        return $this->hasOne(PersonalData::class);
    }

    public function workExperiences(): HasMany
    {
        return $this->hasMany(WorkExperience::class);
    }

    public function languages(): BelongsToMany
    {
        return $this->belongsToMany(Language::class, 'cvs_languages')->withPivot('level');
    }

    public function digitalSkills(): BelongsToMany
    {
        return $this->belongsToMany(DigitalSkill::class, 'cvs_digital_skills');
    }

    public function education(): HasMany
    {
        return $this->hasMany(Education::class);
    }

    public function drivingLicenses(): BelongsToMany
    {
        return $this->belongsToMany(DrivingLicense::class, 'cvs_driving_licenses');
    }
}
