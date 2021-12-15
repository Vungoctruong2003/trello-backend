<?php

namespace App\Http\Controllers;

use App\Models\User_group;
use Illuminate\Http\Request;

class UserGroupController extends Controller
{
    public function store(Request $request)
    {
        try {
            $user_board = new User_group();
            $user_board->user_id = $request->user_id;
            $user_board->group_id = $request->group_id;
            $user_board->role = 3;
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
