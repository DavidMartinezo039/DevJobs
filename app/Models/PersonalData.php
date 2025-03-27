<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class PersonalData extends Model
{
    use HasFactory;

    protected $table = 'personal_data';

    protected $fillable = [
        'cvs_id',
        'first_name',
        'last_name',
        'image',
        'about_me',
        'work_permits',
        'birth_date',
        'city',
        'country',
        'nationality',
        'email',
        'address',
    ];

    protected $casts = [
        'work_permits' => 'array',
        'nationality' => 'array',
        'email' => 'array',
        'address' => 'array',
    ];

    public function cv()
    {
        return $this->belongsTo(CV::class, 'cvs_id');
    }
}
