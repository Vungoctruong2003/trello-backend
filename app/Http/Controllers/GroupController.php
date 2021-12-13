<?php

namespace App\Http\Controllers;

use App\Models\Group;
use App\Models\User_group;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class GroupController extends Controller
{
    public function store(Request $request)
    {
        try {
            $group = new Group();
            $group->title = $request->title;
            $group->policy = $request->policy;
            $group->save();
            $id =  Group::orderBy('id','desc')->limit(1)->get();
            $user_board = new User_group();
            $user_board->user_id = Auth::user()->id;
            $user_board->group_id = $id[0]['id'];
            $user_board->role = 1;
            $user_board->save();
            $data = [
                'status' => 'success'
            ];
        } catch (\Exception $exception) {
            $data = [
                'status' => 'error',
                'message' => $exception
            ];
        }
        return response()->json($data);
    }
}
