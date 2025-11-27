<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class Answer extends Model
{
    use HasFactory;

    protected $fillable = [
        'q_id',
        'answer_text',
        'right_answer',
    ];

    // Egy válasz egy kérdéshez tartozik
    public function question()
    {
        return $this->belongsTo(Question::class, 'q_id');
    }
}
