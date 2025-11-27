<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class Question extends Model
{
    use HasFactory;

    // Ezeket lehet tömegesen kitölteni (pl. Question::create([...]))
    protected $fillable = [
        'question_text',
        'difficulty',
    ];

    // Egy kérdéshez több válasz tartozik
    public function answers()
    {
        return $this->hasMany(Answer::class, 'q_id');
    }
}
