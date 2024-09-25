<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;

class Exam extends Model
{
    use HasFactory;

    protected $fillable = [
        'classrooms_id',
        'courses_id',
        'study_period',
        'session',
        'type'
    ];

    public function classroom(): BelongsTo
    {
        return $this->belongsTo(Classroom::class, 'classrooms_id', 'id');
    }

    public function course(): BelongsTo
    {
        return $this->belongsTo(Course::class, 'courses_id', 'id');
    }

    public function sessions(): HasMany
    {
        return $this->hasMany(ExamSession::class, 'exams_id', 'id');
    }

    public function questions(): HasMany
    {
        return $this->hasMany(Question::class, 'exams_id', 'id');
    }

    public function userAnswers(): HasMany
    {
        return $this->hasMany(UserAnswer::class, 'exams_id', 'id');
    }

    public function userSheets(): HasMany
    {
        return $this->hasMany(UserSheet::class, 'exams_id', 'id');
    }
}
