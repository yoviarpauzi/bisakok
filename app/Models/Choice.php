<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;

class Choice extends Model
{
    use HasFactory;

    protected $fillable = ['questions_id', 'choice', 'is_correct'];

    public function question(): BelongsTo
    {
        return $this->belongsTo(Question::class);
    }

    public function userAnswers(): HasMany
    {
        return $this->hasMany(UserAnswer::class, 'choices_id', 'id');
    }
}
