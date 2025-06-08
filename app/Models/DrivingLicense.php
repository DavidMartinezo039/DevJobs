<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsToMany;

/**
 * @OA\Schema(
 *     schema="DrivingLicense",
 *     title="Driving License",
 *     description="Driving license model",
 *     @OA\Property(property="id", type="integer", example=1),
 *     @OA\Property(property="category", type="string", example="B"),
 *     @OA\Property(property="description", type="string", example="Can drive cars"),
 *     @OA\Property(property="only_god", type="boolean", example=true),
 *     @OA\Property(property="created_at", type="string", format="date-time"),
 *     @OA\Property(property="updated_at", type="string", format="date-time")
 * )
 */
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

    public function scopeOrderedByCategory($query)
    {
        return $query->orderBy('category');
    }

    public function cvs(): BelongsToMany
    {
        return $this->belongsToMany(CV::class, 'cvs_driving_licenses', 'driving_license_id', 'cv_id')->withTimestamps();
    }
}
