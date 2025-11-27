<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\Question;
use App\Models\Answer;

class QuestionSeeder extends Seeder
{
    public function run(): void
    {
        $questions = [
            ['question_text' => 'Kérdés szövege 1?', 'difficulty' => 'easy'],
            ['question_text' => 'Kérdés szövege 2?', 'difficulty' => 'medium'],
            ['question_text' => 'Kérdés szövege 3?', 'difficulty' => 'easy'],
            ['question_text' => 'Kérdés szövege 4?', 'difficulty' => 'easy'],
            ['question_text' => 'Kérdés szövege 5?', 'difficulty' => 'medium'],
            ['question_text' => 'Kérdés szövege 6?', 'difficulty' => 'easy'],
            ['question_text' => 'Kérdés szövege 7?', 'difficulty' => 'easy'],
            ['question_text' => 'Kérdés szövege 8?', 'difficulty' => 'medium'],
            ['question_text' => 'Kérdés szövege 9?', 'difficulty' => 'easy'],
            ['question_text' => 'Kérdés szövege 10?', 'difficulty' => 'easy'],
            ['question_text' => 'Kérdés szövege 11?', 'difficulty' => 'medium'],
            ['question_text' => 'Kérdés szövege 12?', 'difficulty' => 'easy'],
            ['question_text' => 'Kérdés szövege 13?', 'difficulty' => 'easy'],
            ['question_text' => 'Kérdés szövege 14?', 'difficulty' => 'medium'],
            ['question_text' => 'Kérdés szövege 15?', 'difficulty' => 'easy'],
        ];

        foreach ($questions as $i => $q) {
            $question = Question::create($q);

            // 4 válasz létrehozása
            for ($j = 1; $j <= 4; $j++) {
                Answer::create([
                    'q_id' => $question->id,
                    'answer_text' => ($i + 1) . '. kérdés ' . $j . '. válasz',
                    'right_answer' => $j === 1, // az első mindig helyes
                ]);
            }
        }
    }
}
