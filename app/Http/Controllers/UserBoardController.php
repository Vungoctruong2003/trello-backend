<?php

namespace App\Http\Controllers;

use App\Models\User_board;
use Illuminate\Http\Request;

class UserBoardController extends Controller
{
    public function store(Request $request)
    {
        try {
            $user_board = new User_board();
            $user_board->user_id = $request->user_id;
            $user_board->group_id = $request->group_id;
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
