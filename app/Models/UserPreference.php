<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class UserPreference extends Model
{
    use HasFactory;

    protected $fillable = [
        'user_id',
        'category_id',
        'salary_id',
        'company',
        'keyword',
    ];

    public function scopeMatchingVacancy($query, $vacancy)
    {
        return $query->where(function ($q) use ($vacancy) {
            $q->where(function ($subQuery) use ($vacancy) {
                $subQuery->whereNotNull('salary_id')
                    ->where('salary_id', $vacancy->salary_id);
            })->orWhereNull('salary_id');
        })->where(function ($q) use ($vacancy) {
            $q->where(function ($subQuery) use ($vacancy) {
                $subQuery->whereNotNull('category_id')
                    ->where('category_id', $vacancy->category_id);
            })->orWhereNull('category_id');
        })->where(function ($q) use ($vacancy) {
            $q->where(function ($subQuery) use ($vacancy) {
                $subQuery->whereNotNull('company')
                    ->where('company', $vacancy->company);
            })->orWhereNull('company');
        })->where(function ($q) use ($vacancy) {
            $q->where(function ($subQuery) use ($vacancy) {
                $subQuery->whereNotNull('keyword')
                    ->where('keyword', '!=', '')
                    ->where(function ($kw) use ($vacancy) {
                        $kw->whereRaw('? LIKE \'%\' || keyword || \'%\'', [$vacancy->title])
                            ->orWhereRaw('? LIKE \'%\' || keyword || \'%\'', [$vacancy->description]);
                    });
            })->orWhereNull('keyword')->orWhere('keyword', '');
        })
            ->where(function ($query) {
                $query->whereNotNull('salary_id')
                    ->orWhereNotNull('category_id')
                    ->orWhereNotNull('company')
                    ->orWhere(function ($q) {
                        $q->whereNotNull('keyword')
                            ->where('keyword', '!=', '');
                    });
            });
    }

    public function user(): BelongsTo
    {
        return $this->belongsTo(User::class);
    }

    public function category(): BelongsTo
    {
        return $this->belongsTo(Category::class);
    }

    public function salary(): BelongsTo
    {
        return $this->belongsTo(Salary::class);
    }
}
