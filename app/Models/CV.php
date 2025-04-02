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
        return $this->belongsTo(User::class)->withTimestamps();
    }

    public function personalData(): HasOne
    {
        return $this->hasOne(PersonalData::class)->withTimestamps();
    }

    public function workExperiences(): HasMany
    {
        return $this->hasMany(WorkExperience::class)->withTimestamps();
    }

    public function languages(): BelongsToMany
    {
        return $this->belongsToMany(Language::class, 'cvs_languages', 'cv_id', 'language_id')->withPivot('level')->withTimestamps();
    }

    public function digitalSkills(): BelongsToMany
    {
        return $this->belongsToMany(DigitalSkill::class, 'cvs_digital_skills', 'cv_id', 'digital_skill_id')->withTimestamps();
    }

    public function education(): HasMany
    {
        return $this->hasMany(Education::class)->withTimestamps();
    }

    public function drivingLicenses(): BelongsToMany
    {
        return $this->belongsToMany(DrivingLicense::class, 'cvs_driving_licenses', 'cv_id', 'driving_license_id')->withTimestamps();
    }
}
