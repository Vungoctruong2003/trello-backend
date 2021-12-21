<?php

namespace App\Http\Controllers;

use App\Models\User_group;
use App\Models\User_board;
use Illuminate\Http\Request;
use App\Models\Board;
use function PHPUnit\Framework\isEmpty;

class UserGroupController extends Controller
{
    public function store(Request $request)
    {
        try {
            $user = User_group::where('user_id', $request->user_id)->where('group_id', $request->group_id)->get();
            if ($user->isEmpty()){
                $user_board = new User_group();
                $user_board->user_id = $request->user_id;
                $user_board->group_id = $request->group_id;
                $user_board->role = 2;
                $user_board->save();
                $data = [
                    'status' => 'success'
                ];
            }else{
                $data = [
                    'status' => 'error',
                    'message' => 'Người dùng đã ở trong nhóm này'
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

    public function index($id)
    {
        try {
            $users = User_group::where('group_id', $id)->with('user')->get();
                $data = [
                    'status' => 'success',
                    'data' => $users
                ];
        } catch (\Exception $exception) {
            $data = [
                'status' => 'error',
                'message' => $exception
            ];
        }
        return response()->json($data);
    }

    public function changeRole(Request $request, $id){
        try {
        $user = User_group::findOrFail($id);
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

    public function delete(Request $request){
        try {
            
            $boards = Board::where('group_id',$request->group_id)->get();
            foreach ($boards as $board) {
                 User_board::where('board_id',$board->id)->where('user_id',$request->id)->delete();
            }
            User_group::where('group_id',$request->group_id)->where('user_id',$request->id)->delete();
            $data = [
                'status' => 'success',
            ];
        } catch (\Exception $exception) {
            $data = [
                'status' => 'error',
                'message' => $exception
            ];
        }
        return response()->json($data);
    }

    public function outGroup($id){
        try {
        $user = User_group::where('user_id',Auth::user()->id)->where('group_id',$id);
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
