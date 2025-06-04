<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsToMany;

class DrivingLicense extends Model
{
    use HasFactory;

    protected $fillable = [
        'only_god',
        'category',
        'vehicle_type',
        'max_speed',
        'max_power',
        'power_to_weight',
        'max_weight',
        'max_passengers',
        'min_age',
    ];

    public function cvs(): BelongsToMany
    {
        return $this->belongsToMany(CV::class, 'cvs_driving_licenses', 'driving_license_id', 'cv_id')->withTimestamps();
    }
}
