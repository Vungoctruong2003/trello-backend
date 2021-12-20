<?php

namespace App\Http\Controllers;

use App\Models\Card;
use App\Models\Comment;
use App\Models\List_card;
use App\Models\Tag;
use App\Models\User_board;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use function PHPUnit\Framework\isEmpty;

class ListCardController extends Controller
{
    public function index($id)
    {
        try {
            $listCards = [];
            $user_id = Auth::user()->id;
            $lists = List_card::where('board_id', $id)->orderBy('seq', 'asc')->get();
            foreach ($lists as $list) {
                $cards = Card::where('list_id', $list['id'])->orderBy('seq', 'asc')->get();
                array_push($listCards, $cards);
            }
            $role = User_board::where('board_id', $id)->where('user_id', $user_id)->get('role');
            $data = [
                'status' => 'success',
                'cards' => $listCards,
                'lists' => $lists,
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
                'message' => $exception,
                'data' => $request->lists
            ];
        }
        return response()->json($data);
    }

    public function deleteList($id)
    {

        try {
            $cards = Card::where('list_id', $id)->get();
            foreach ($cards as $card) {
                Comment::where('card_id',$card->id)->delete();
                Tag::where('card_id',$card->id)->delete();
                Card::destroy($card->id);
            }
            List_card::findOrFail($id)->delete();
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

