<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\BelongsToMany;

class PersonalData extends Model
{
    use HasFactory;

    protected $table = 'personal_data';

    protected $fillable = [
        'cvs_id', 'first_name', 'last_name', 'image', 'about_me',
        'work_permits', 'birth_date', 'city', 'country', 'nationality',
        'email', 'address', 'gender_id'
    ];

    protected $casts = [
        'work_permits' => 'array',
        'nationality' => 'array',
        'email' => 'array',
        'address' => 'array',
    ];

    public function cv(): BelongsTo
    {
        return $this->belongsTo(Cv::class, 'cv_id')->withTimestamps();
    }

    public function gender(): BelongsTo
    {
        return $this->belongsTo(Gender::class)->withTimestamps();
    }

    public function identities(): BelongsToMany
    {
        return $this->belongsToMany(Identity::class, 'identity_personal_data')
            ->withPivot('identity_number')->withTimestamps();
    }

    public function phones(): BelongsToMany
    {
        return $this->belongsToMany(Phone::class, 'personal_data_phones')
            ->withPivot('number')->withTimestamps();
    }

    public function socialMedia(): BelongsToMany
    {
        return $this->belongsToMany(SocialMedia::class, 'personal_data_social_media')
            ->withPivot('user_name', 'url')->withTimestamps();
    }
}
