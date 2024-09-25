<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;

class ExamSession extends Model
{
    use HasFactory;

    protected $fillable = ['exams_id', 'start_at', 'end_at'];

    public function exam(): BelongsTo
    {
        return $this->belongsTo(Exam::class, 'exams_id', 'id');
    }

    public function studentExamSessions(): HasMany
    {
        return $this->hasMany(UserExamSession::class, 'exam_sessions_id', 'id');
    }
}
