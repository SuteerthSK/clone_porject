<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\ReadingChallenge;

class ReadingChallengeController extends Controller
{
    public function createOrUpdate(Request $r)
    {
        $user = auth('api')->user();
        if (!$user) return response()->json(['error'=>'Unauthorized'],401);

        $data = $r->validate(['year'=>'required|integer','goal_count'=>'required|integer|min:1']);
        $rc = ReadingChallenge::updateOrCreate(
            ['user_id'=>$user->id,'year'=>$data['year']],
            ['goal_count'=>$data['goal_count']]
        );
        return response()->json($rc,201);
    }
}
