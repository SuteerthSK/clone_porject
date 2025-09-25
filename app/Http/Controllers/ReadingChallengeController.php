<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\ReadingChallenge;
use Illuminate\Support\Facades\Auth;

class ReadingChallengeController extends Controller
{
    /**
     * Show the form for editing the reading challenge.
     */
    public function edit()
    {
        $user = Auth::user();
        $year = now()->year;

        // Find the challenge. If it doesn't exist, create it with default values.
        $challenge = ReadingChallenge::firstOrCreate(
            ['user_id' => $user->id, 'year' => $year],
            ['goal_count' => 20] // Default goal from your setup
        );

        return view('challenges.edit', compact('challenge'));
    }

    /**
     * Update the user's reading challenge goal.
     */
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

        // Redirect back to the main books page with a success message
        return redirect()->route('books.index')->with('status', 'Reading goal updated successfully!');
    }
}