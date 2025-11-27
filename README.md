# Quiz készítő 

## Feladat

Készíts olyan programot, mely segítségével egy quizt készíthetünk. 

A képernyőterveket a RESOURCES mappában találod. 

1. A quizhez kérdések és kérdésenként max 4 válasz tartozik, mely közül pontosan egy a helyes.
2. Az új  kérdéseket és válaszokat egy erre kialakított felületen tudjuk megadni.  
3. A kérdések háromféle nehézségi szinthez tartozhatnak: könnyű, közepes, nehéz. 
4. Ha a felhasználó meg akar oldani egy quizt, akkor a rendszer véletlenszerűen ad neki 10 különböző kérdést a feladatbankból. 
5. A válaszra kattintva:
    - helyes válasz esetén zöldre változik a háttérszín.
    - helytelen válasz esetén pirosra. 
6. A rendszer számolja a pontokat, melyet a quiz végén el is lehet menteni. Ekkor meg kell adni egy nevet, a rendszer a névhez elmenti a quiz nehézségi szintjét (könnyű, közepes, nehéz, vegyes), és a pontszámot. 
7. Az elmentett pontszámok alapján lehet toplistát megjeleníteni. 

### Egyszerűsítések: 

- Jelen programban az összes quiz kérdés egyetlen quizhez fog tartozni.
- Minden kérdéshez max 4 választ lehet megadni, és abból pontosan egy a helyes.
- A programban nincs bejelentkezés, felhasználókezelés, de elmenthetjük a quiz eredményét.
- Első körben nyilvános (nem védett) API végpontokat kell készíteni. 

# Adatbázis

## USERS

| oszlop      | típus        |
|-------------|--------------|
| id          | INT (PK, AI) |
| name        | VARCHAR      |
| point       | INT          |
| timestamp   | TIMESTAMP    |

---

## QUESTION

| oszlop          | típus                                  |
|-----------------|-----------------------------------------|
| id              | INT (PK, AI)                            |
| question_text   | TEXT                                    |
| difficulty      | ENUM(könnyű, közepes, nehéz)            |

---

## ANSWER

| oszlop        | típus                      |
|---------------|-----------------------------|
| id            | INT (PK, AI)                |
| q_id          | INT (FK → QUESTION.id)      |
| answer_text   | TEXT                        |
| right_answer  | BOOLEAN                     |


## Adatbázis kapcsoalti ábra

        ┌───────────────┐
        │    USERS      │
        ├───────────────┤
        │ id (PK)       │
        │ name          │
        │ point         │
        │ timestamp     │
        └───────────────┘


           
        ┌───────────────┐          ┌───────────────────┐
        │   QUESTION    │          │     ANSWER        │
        ├───────────────┤          ├───────────────────┤
        │ id (PK)       │   1→N    │ id (PK)           │
        │ question_text │──────────│ q_id (FK)         │
        │ difficulty    │          │ answer_text       │
        └───────────────┘          │ right_answer      │
                                   └───────────────────┘
#  Backend lépésről lépésre

1. projekt létrehozása
2. .env beállítása
3. Adatbázis táblák migrácoó elkészítése a kapcsolatokkal
4. modellek
5. seederek, factory-k
6. controllerek elkészítése
7. rootes - api végpontok létrehozása

### Projekt létrehozása

```
composer create-project laravel/laravel quiz-backend   
```

Mivel a laravel PAI kiszolgálóként fogjuk használni: 

```
php artisan install:api
```

### Repository létrehozása

Érdemes az elején létrehozni a git könyvtárt, mert segít eligazodni a mapparendszerben. 

### .env fájl módosítása

```
DB_CONNECTION=mysql
DB_HOST=127.0.0.1
DB_PORT=3306
DB_DATABASE=quiz
DB_USERNAME=root
DB_PASSWORD=
```

## Adatbázis - database mappa

**database mappában dolgozz tovább!**

1. Users tábla módosítása
2. Questions és Answers tábla létrehozása

Vigyázz! Nem mindegy milyen sorrendben hozod létre! Először a questions tábla kell, mert az answers tábla használja a questions kulcsát. 

php artisan make:migration create_questions_table
php artisan make:migration create_answers_table

3.  Questions migrációs fájlban cseréld le az up függvényt

```php

   public function up(): void
    {
        Schema::create('questions', function (Blueprint $table) {
            $table->id();
            $table->text('question_text');
            $table->enum('difficulty', ['easy', 'medium', 'hard']);
            $table->timestamps();
        });
    }
```

4. Answers migrációs fájlban cseréld le az up függvényt

```php
    public function up(): void
    {
        Schema::create('answers', function (Blueprint $table) {
            $table->id();
            $table->foreignId('q_id')->constrained('questions')->onDelete('cascade');
            $table->text('answer_text');
            $table->boolean('right_answer')->default(false);
            $table->timestamps();
        });
    }

```

5. Migráld az adatbázist!

```php
php artisan migrate
```

6. Modell, seeder/factory létrehozása a táblákhoz

```
php artisan make:model Question -fs
php artisan make:model Answer -fs
```

7. Seeder használata

A QuestionSeeder fájlba: 
```php
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

```

8. Frissítsd az adatbázist a seeder adataival!

```
php artisan db:seed
```
## Modellek - app/Models mappa

Az előzőekben már létrehoztuk a modelleket, most kiegészítjük őket. 
A Laravel modellekben alapvetően két dolgot kell megadni:

1. Mely mezőket lehet tömegesen kitölteni ($fillable)

2. Kapcsolatok definiálása 
    - egyik kérdésnek több válasza van: **hasMany**
    - minden válasz egy kérdéshez tartozik: **belongsTo**

### Question modell

```php
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
```

### Answer modell

```php
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

```

## Controllerek elkészítése - app/Http/Controllers mappában

Az alábbi utasítások létrehozzák a controller osztályokat az index, store, show, update, destroy metódusokkal.

```
php artisan make:controller QuestionController --api
php artisan make:controller AnswerController --api

```

### QuestionController 

#### index metódus

Az összes kérdés lekérése a hozzájuk tartozó válaszokkal. A visszatérési érték egy JSON formátumó adathalmaz. 

```php
public function index()
    {
        // Lekérdezzük az összes kérdést, és betöltjük az összes válaszát
        $questions = Question::with('answers')->get();

        // Visszaadjuk JSON-ként (API-hoz)
        return response()->json($questions);
    }
```
#### store metódus

A metódus kap egy JSON formátumó adathalmazt egy kérdéssel és a hozzá tartozó válaszokkal. 
```php
{
  "question_text": "Mi a fővárosa Magyarországnak?",
  "difficulty": "easy",
  "answers": [
    {"answer_text": "Budapest", "right_answer": true},
    {"answer_text": "Debrecen", "right_answer": false},
    {"answer_text": "Szeged", "right_answer": false},
    {"answer_text": "Pécs", "right_answer": false}
  ]
}
```

```php
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
    $question = Question::create([
        'question_text' => $validated['question_text'],
        'difficulty' => $validated['difficulty'],
    ]);

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

```


## API végpontok létrehozása  - routes mappa

Hozd létre az api.php fájlt!
Hozd létre a végpontokat!

```php
<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\QuestionController;

Route::get('/questions', [QuestionController::class, 'index']);
Route::post('/question', [QuestionController::class, 'store']);
Route::get('/questions/{id}', [QuestionController::class, 'show']);
Route::put('/questions/{id}', [QuestionController::class, 'update']);
Route::delete('/questions/{id}', [QuestionController::class, 'destroy']);
```


Ha minden jól megy, akkor ha a böngészőbe beírod http://127.0.0.1:8000/api/questions végpontot, akkor megkapod a az adatbázisban lévő összes kérdést és választ!
illetve tesztelheted Thunder-ben is. 







