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
        'cv_id', 'first_name', 'last_name', 'image', 'about_me',
        'workPermits', 'birth_date', 'city', 'country', 'nationality',
        'email', 'address', 'gender_id'
    ];

    protected $casts = [
        'workPermits' => 'array',
        'nationality' => 'array',
        'email' => 'array',
        'address' => 'array',
    ];

    public function gender(): BelongsTo
    {
        return $this->belongsTo(Gender::class);
    }

    public function identities(): BelongsToMany
    {
        return $this->belongsToMany(Identity::class, 'identity_personal_data')
            ->withPivot('number')->withTimestamps();
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

    public function cv(): BelongsTo // <-- Asegúrate de que el nombre del método sea 'cv' (todo minúscula)
    {
        return $this->belongsTo(CV::class);
    }
}
