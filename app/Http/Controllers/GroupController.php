<?php

namespace App\Http\Controllers;

use App\Models\Board;
use App\Models\Card;
use App\Models\Comment;
use App\Models\Group;
use App\Models\List_card;
use App\Models\Tag;
use App\Models\User_board;
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
            $id = Group::orderBy('id', 'desc')->limit(1)->get();
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

    public function update(Request $request, $id)
    {
        try {
            $group = Group::findOrFail($id);
            $group->title = $request->title;
            $group->policy = $request->policy;
            $group->save();
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
            $groups = User_group::where('user_id', $id)->with('group')->get();
            $data = [
                'status' => 'success',
                'data' => $groups
            ];
        } catch (\Exception $exception) {
            $data = [
                'status' => 'error',
                'message' => $exception
            ];
        }
        return response()->json($data);
    }

    public function getRole($id)
    {
        try {
            $group = User_group::where('user_id', Auth::user()->id)->where('group_id', $id)->get();
            $data = [
                'status' => 'success',
                'data' => $group[0]->role
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
            $boards = Board::where('group_id', $id)->get();
            foreach ($boards as $board) {
                $lists = List_card::where('board_id', $board->id)->get();
                foreach ($lists as $list) {
                    $cards = Card::where('list_id', $list->id)->get();
                    foreach ($cards as $card) {
                        Comment::where('card_id',$card->id)->delete();
                        Tag::where('card_id',$card->id)->delete();
                        Card::where('id',$card->id)->delete();
                    }
                    List_card::where('id',$list->id)->delete();
                }
                User_board::where('board_id',$board->id)->delete();
                Board::where('id',$board->id)->delete();
            }
            User_group::where('group_id',$id)->delete();
            Group::destroy($id);
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

}
