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
            $id = User::where('email',$request->email)->get();
            $id = $id[0]['id'];
            $group_id = Board::findOrFail($request->board_id)->group_id;
            $user = User_group::where('user_id', $id)->where('group_id', $group_id)->get();
            if (!$user->isEmpty()) {
                $user = User_board::where('user_id', $id)->where('board_id', $request->board_id)->get();
                    if ($user->isEmpty()){
                        $user_board = new User_board();
                        $user_board->user_id = $id;
                        $user_board->board_id = $request->board_id;
                        $user_board->role = $request->role;
                        $user_board->save();
                        $data = [
                            'status' => 'success'
                        ];
                    }else{
                        $data = [
                            'status' => 'error1',
                            'message' => 'Người dùng đã ở trong bảng này'
                        ];
                    }
            } else {
                $data = [
                    'status' => 'error2',
                    'message' => 'Người này chưa có trong nhóm'
                ];
            }
        } catch (\Exception $exception) {
            $data = [
                'status' => 'error',
                'message' => 'Người dùng không tồn tại'
            ];
        }
        return response()->json($data);
    }

    public function changeRole(Request $request, $id){
        try {
        $user = User_board::findOrFail($id);
        $user->role = $request->role;
        $user->save();
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

    public function delete($id){
        try {
        $user = User_board::findOrFail($id);
        $user->delete();
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
