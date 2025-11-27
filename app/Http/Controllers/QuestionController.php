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
        // Lekérdezzük az összes kérdést, és betöltjük az összes válaszát
        $questions = Question::with('answers')->get();

        // Visszaadjuk JSON-ként (API-hoz)
        return response()->json($questions);
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        // Validáció
        $validated = $request->validate([
            'question_text' => 'required|string',
            'difficulty' => 'required|in:easy,medium,hard',
            'answers' => 'required|array|min:2|max:4',
            'answers.*.answer_text' => 'required|string',
            'answers.*.right_answer' => 'required|boolean',
        ]);

        // Ellenőrizzük, hogy pontosan egy helyes válasz legyen
        $correctCount = collect($validated['answers'])->where('right_answer', true)->count();
        if ($correctCount !== 1) {
            return response()->json([
                'message' => 'Pontosan egy helyes választ kell megjelölni!'
            ], 422);
        }

        // Kérdés létrehozása
        Question::create([
            'question_text' => $validated['question_text'],
            'difficulty' => $validated['difficulty'],
        ]);

        // Lekérjük az utoljára létrehozott kérdést
        $question = Question::latest('id')->first();

        // Válaszok létrehozása
        foreach ($validated['answers'] as $answerData) {
            $question->answers()->create($answerData);
        }

        // Visszaadjuk az adatbázisból frissen betett kérdést a válaszokkal
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
        //
    }
}
