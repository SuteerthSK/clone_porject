<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Group;
use App\Models\DiscussionPost;

class GroupController extends Controller
{
    public function store(Request $r)
    {
        $user = auth('api')->user();
        if (!$user) return response()->json(['error'=>'Unauthorized'],401);

        $data = $r->validate(['name'=>'required','description'=>'nullable','is_private'=>'boolean']);
        $g = Group::create([
            'name'=>$data['name'],
            'description'=>$data['description'] ?? null,
            'is_private'=>$data['is_private'] ?? false,
            'owner_id'=>$user->id
        ]);
        $g->members()->syncWithoutDetaching([$user->id]);
        return response()->json($g,201);
    }

    public function join(Group $group)
    {
        $user = auth('api')->user();
        if (!$user) return response()->json(['error'=>'Unauthorized'],401);

        $group->members()->syncWithoutDetaching([$user->id]);
        return response()->json(['joined'=>true]);
    }

    public function post(Request $r, Group $group)
    {
        $user = auth('api')->user();
        if (!$user) return response()->json(['error'=>'Unauthorized'],401);

        $p = DiscussionPost::create([
            'group_id'=>$group->id,
            'user_id'=>$user->id,
            'body'=>$r->input('body')
        ]);
        return response()->json($p,201);
    }
}
