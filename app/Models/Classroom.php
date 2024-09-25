<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasMany;

class Classroom extends Model
{
    use HasFactory;

    protected $fillable = ['name'];

    public function students(): HasMany
    {
        return $this->hasMany(User::class, 'classrooms_id', 'id');
    }

    public function exams(): HasMany
    {
        return $this->hasMany(Exam::class, 'classrooms_id', 'id');
    }
}
