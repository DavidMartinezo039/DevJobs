<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsToMany;

class DigitalSkill extends Model
{
    use HasFactory;

    protected $fillable = ['name'];

    public function cvs(): BelongsToMany
    {
        return $this->belongsToMany(CV::class, 'cvs_digital_skills');
    }
}
