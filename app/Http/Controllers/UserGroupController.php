<?php

namespace App\Http\Controllers;

use App\Models\User_group;
use Illuminate\Http\Request;
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
                $user_board->role = 1;
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
}
