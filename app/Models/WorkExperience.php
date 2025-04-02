<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class WorkExperience extends Model {
    use HasFactory;
    protected $fillable = ['cv_id', 'company_name', 'position', 'start_date', 'end_date', 'description'];

    public function cv(): BelongsTo
    {
        return $this->belongsTo(CV::class)->withTimestamps();
    }
}
