<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class UserAnswer extends Model
{
    use HasFactory;

    protected $fillable = ['users_id', 'exams_id', 'questions_id', 'choices_id'];

    public function user(): BelongsTo
    {
        return $this->belongsTo(User::class, 'users_id', 'id');
    }

    public function exam(): BelongsTo
    {
        return $this->belongsTo(Exam::class, 'exams_id', 'id');
    }

    public function question(): BelongsTo
    {
        return $this->belongsTo(Question::class, 'questions_id', 'id');
    }

    public function answer(): BelongsTo
    {
        return $this->belongsTo(Choice::class, 'choices_id', 'id');
    }
}
