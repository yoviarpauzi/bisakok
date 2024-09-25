<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;

class Question extends Model
{
    use HasFactory;

    protected $fillable = ['exams_id', 'question'];

    public function exam(): BelongsTo
    {
        return $this->belongsTo(Exam::class, 'exams_id', 'id');
    }

    public function choices(): HasMany
    {
        return $this->hasMany(Choice::class, 'questions_id', 'id');
    }

    public function userAnswers(): HasMany
    {
        return $this->hasMany(UserAnswer::class, 'questions_id', 'id');
    }
}
