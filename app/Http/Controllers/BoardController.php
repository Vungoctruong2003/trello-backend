<?php

namespace App\Http\Controllers;

use App\Models\Board;
use App\Models\Card;
use App\Models\List_card;
use App\Models\User_board;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class BoardController extends Controller
{
    public function store(Request $request)
    {
        try {
            $board = new Board();
            $board->title = $request->title;
            $board->policy = $request->policy;
            $board->create_by = Auth::user()->id;
            $board->group_id = $request->group_id;
            $board->save();
            $id = Board::orderBy('id', 'desc')->limit(1)->get();
            $user_board = new User_board();
            $user_board->user_id = Auth::user()->id;
            $user_board->board_id = $id[0]['id'];
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

    public function update(Request $request, $id)
    {
        try {
            $broad = Board::findOrFail($id);
            $broad->title = $request->title;
            $broad->policy = $request->policy;
            $broad->save();
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

    public function delete($id)
    {
        try {
            $members = User_board::where('board_id', $id)->get();
            foreach ($members as $member) {
                User_board::destroy($member->id);
            }
            $lists = List_card::where('board_id', $id)->get();
            foreach ($lists as $list) {
                $cards = Card::where('list_id', $list->id)->get();
                foreach ($cards as $card) {
                    Card::destroy($card->id);
                }
                List_card::destroy($list->id);
            }
            Board::destroy($id);
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

    public function index()
    {
        try {
            $id = Auth::user()->id;
            $boards = User_board::where('user_id', $id)->with('board')->get();
            $data = [
                'status' => 'success',
                'data' => $boards
            ];
        } catch (\Exception $exception) {
            $data = [
                'status' => 'error',
                'message' => $exception
            ];
        }
        return response()->json($data);
    }

    public function createByMe()
    {
        try {
            $id = Auth::user()->id;
            $boards = Board::where('create_by', $id)->get();
            $data = [
                'status' => 'success',
                'data' => $boards
            ];
        } catch (\Exception $exception) {
            $data = [
                'status' => 'error',
                'message' => $exception
            ];
        }
        return response()->json($data);
    }

    public function getById($id)
    {
        try {
            $board = Board::findOrFail($id);
            $data = [
                'status' => 'success',
                'data' => $board
            ];
        } catch (\Exception $exception) {
            $data = [
                'status' => 'error',
                'message' => $exception
            ];
        }
        return response()->json($data);
    }

    public function getRole($id){
        try {
            $board = User_board::where('user_id',Auth::user()->id)->where('board_id',$id)->get();
            $data = [
                'status' => 'success',
                'data' => $board[0]->role
            ];
        } catch (\Exception $exception) {
            $data = [
                'status' => 'error',
                'message' => $exception
            ];
        }
        return response()->json($data);
    }

    public function getUsers($id){
        try {
            $users = User_board::where('board_id',$id)->with('user')->get();
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
}
