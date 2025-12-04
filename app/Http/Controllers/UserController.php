<?php

namespace App\Http\Controllers;

use App\Models\User;
use Illuminate\Http\Request;

class UserController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        //
           $users = User::get();
        return response()->json($users);
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        /* {name: 'fdssdf', point: 2} */
         $validated = $request->validate([
            'name' => 'required|string',
            'point' => 'required|integer',          
        ]);

        $user = User::create([
            'name' => $validated['name'],
            'point' => $validated['point'],
        ]);
       
        $userWithPoint = User::find($user->id);
        return response()->json([
            'message' => 'Kérdés és válaszok sikeresen létrehozva.',
            'question' => $userWithPoint
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
