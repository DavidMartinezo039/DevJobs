<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class EditRequest extends Model
{
    use HasFactory;

    protected $fillable = ['driving_license_id', 'requested_by', 'approved'];

    public function drivingLicense(): BelongsTo
    {
        return $this->belongsTo(DrivingLicense::class);
    }

    public function user(): BelongsTo
    {
        return $this->belongsTo(User::class, 'requested_by');
    }
}
