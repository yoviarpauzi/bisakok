<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasMany;

class Course extends Model
{
    use HasFactory;

    protected $fillable = ['name'];

    public function exams(): HasMany
    {
        return $this->hasMany(Exam::class, 'courses_id', 'id');
    }
}
