<?php

namespace App\Http\Controllers;

use App\Models\Board;
use App\Models\User;
use App\Models\User_board;
use App\Models\User_group;
use Illuminate\Http\Request;
use function PHPUnit\Framework\isEmpty;

class UserBoardController extends Controller
{
    public function store(Request $request)
    {
        try {
            $group_id = Board::findOrFail($request->board_id)->id;
            $user = User_group::where('user_id', $request->user_id)->where('group_id', $group_id)->get();
            if (!$user->isEmpty()) {
                $user_board = new User_board();
                $user_board->user_id = $request->user_id;
                $user_board->board_id = $request->board_id;
                $user_board->role = 1;
                $user_board->save();
                $data = [
                    'status' => 'success'
                ];
            } else {
                $data = [
                    'status' => 'error',
                    'message' => 'Người này chưa có trong nhóm'
                ];
            }
        } catch (\Exception $exception) {
            $data = [
                'status' => 'error',
                'message' => $exception
            ];
        }
        return response()->json($data);
    }
}
