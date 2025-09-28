<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\ReadingChallenge;
use Illuminate\Support\Facades\Auth;

class ReadingChallengeController extends Controller
{
    public function edit()
    {
        $user = Auth::user();
        $year = now()->year;

        
        $challenge = ReadingChallenge::firstOrCreate(
            ['user_id' => $user->id, 'year' => $year],
            ['goal_count' => 20] 
        );

        return view('challenges.edit', compact('challenge'));
    }

    
    public function update(Request $request)
    {
        $user = Auth::user();
        
        $data = $request->validate([
            'year' => 'required|integer',
            'goal_count' => 'required|integer|min:1',
        ]);
        
        ReadingChallenge::updateOrCreate(
            ['user_id' => $user->id, 'year' => $data['year']],
            ['goal_count' => $data['goal_count']]
        );

        
        return redirect()->route('books.index')->with('status', 'Reading goal updated successfully!');
    }
}