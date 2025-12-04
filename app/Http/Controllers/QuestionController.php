<?php

namespace App\Http\Controllers;

use App\Models\Question;
use Illuminate\Http\Request;

class QuestionController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        //
        $questions = Question::with('answers')->get();
        return response()->json($questions);
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        //
        /* {
            "question_text": "Mi a fővárosa Magyarországnak?",
            "difficulty": "easy",
            "answers": [
                {"answer_text": "Budapest", "right_answer": true},
                {"answer_text": "Debrecen", "right_answer": false},
                {"answer_text": "Szeged", "right_answer": false},
                {"answer_text": "Pécs", "right_answer": false}
            ]
        } */
        /* Ellenőrzés, hoyg csak egyetlen jó válasz van megjelölve */
        /* validáció, hogy csak helyes adatok kerülhessenek az adatbázisba */
        $validated = $request->validate([
            'question_text' => 'required|string',
            'difficulty' => 'required|in:easy,medium,hard',
            'answers' => 'required|array|min:2|max:4',
            'answers.*.answer_text' => 'required|string',
            'answers.*.right_answer' => 'required|boolean',
        ]);

        $question = Question::create([
            'question_text' => $validated['question_text'],
            'difficulty' => $validated['difficulty'],
        ]);
        // Válaszok létrehozása
        foreach ($validated['answers'] as $answerData) {
            $question->answers()->create($answerData);
        }
        $questionWithAnswers = Question::with('answers')->find($question->id);
        return response()->json([
            'message' => 'Kérdés és válaszok sikeresen létrehozva.',
            'question' => $questionWithAnswers
        ], 201);
    }

    /**
     * Display the specified resource.
     */
    public function show(string $id)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, string $id)
    {
        //
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(string $id)
    {
        $question = Question::find($id);

        if (!$question) {
            return response()->json([
                'message' => 'A kérdés nem található.'
            ], 404);
        }

        // Törlés (ha a cascade nincs beállítva, a booted() gondoskodik róla)
        $question->delete();

        return response()->json([
            'message' => 'Kérdés és válaszai sikeresen törölve.'
        ], 200);
    }
}
