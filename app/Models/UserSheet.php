<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class UserSheet extends Model
{
    use HasFactory;

    protected $fillable = [
        'users_id',
        'exams_id',
        'num_of_question',
        'num_of_question_answered',
        'num_of_correct_answer',
        'num_of_wrong_answer',
        'score'
    ];

    public function user(): BelongsTo
    {
        return $this->belongsTo(User::class, 'users_id', 'id');
    }

    public function exam(): BelongsTo
    {
        return $this->belongsTo(Exam::class, 'exams_id', 'id');
    }
}
