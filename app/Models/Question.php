<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Question extends Model
{
    //
    use HasFactory;
    protected $fillable = [
        'question_text',
        'difficulty',
    ];
    public function answers()
    {
        return $this->hasMany(Answer::class, 'q_id');
    }
}
