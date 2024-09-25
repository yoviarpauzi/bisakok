<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class UserExamSession extends Model
{
    use HasFactory;

    protected $fillable = ['users_id', 'exam_sessions_id'];

    protected $primaryKey = ['users_id', 'exam_sessions_id'];

    public function student(): BelongsTo
    {
        return $this->belongsTo(User::class, 'users_id', 'id');
    }

    public function examSession(): BelongsTo
    {
        return $this->belongsTo(ExamSession::class, 'exam_sessions_id', 'id');
    }
}
