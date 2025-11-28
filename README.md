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
8. Lehet választani a nehézségi szintek között.

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

### Projekt létrehozása

```
composer create-project laravel/laravel quiz-backend   
```

API kiszolgáló létrehozása:

```
php artisan install:api
```

### Reposytory létrehozása

érdemes az elején létrehozni a git könyvtárt, mert segít eligazodni a mapparendszerben. 









