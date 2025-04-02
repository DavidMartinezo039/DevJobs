<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsToMany;

class DrivingLicense extends Model
{
    use HasFactory;

    protected $fillable = ['type'];

    public function cvs(): BelongsToMany
    {
        return $this->belongsToMany(CV::class, 'cvs_driving_licenses');
    }
}
