<?php

namespace App\Http\Controllers;

use App\Models\List_card;
use App\Models\User_board;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class ListCardController extends Controller
{
    public function index($id)
    {
        try {
            $user_id = Auth::user()->id;
            $lists = List_card::where('board_id', $id)->with('cards')->get();
            $role = User_board::where('board_id', $id)->where('user_id', $user_id)->get('role');
            $data = [
                'status' => 'success',
                'data' => $lists,
                'role' => $role[0]['role']
            ];
        } catch (\Exception $exception) {
            $data = [
                'status' => 'error',
                'message' => $exception
            ];
        }
        return response()->json($data);
    }

    public function store(Request $request)
    {
        try {
            $list = new List_card();
            $list->board_id = $request->board_id;
            $list->title = $request->title;
            $seq = List_card::where('board_id', $request->board_id)->count();
            $list->seq = $seq;
            $list->save();
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

    public function update(Request $request, $id)
    {
        try {
            $list = List_card::findOrFail($id);
            $list->title = $request->title;
            $list->save();
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

    public function changeSeq(Request $request)
    {
        $user_id = Auth::user()->id;
        $role = User_board::where('broad_id', $request[0]['board_id'])->where('user_id', $user_id)->get();
        if ($role == 1) {
            try {
                foreach ($request->lists as $changeList) {
                    $list = List_card::findOrFail($changeList['id']);
                    $list->seq = $changeList['seq'];
                    $list->save();
                }
                $data = [
                    'status' => 'success'
                ];
            } catch (\Exception $exception) {
                $data = [
                    'status' => 'error',
                    'message' => $exception
                ];
            }
        } else {
            $data = [
                'status' => 'error',
                'message' => 'khong co quyen'
            ];
        }
        return response()->json($data);
    }
}
