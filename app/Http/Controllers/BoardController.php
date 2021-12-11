<?php

namespace App\Http\Controllers;

use App\Models\Board;
use App\Models\Card;
use App\Models\List_card;
use App\Models\User_board;
use Illuminate\Http\Request;

class BoardController extends Controller
{
    public function store(Request $request)
    {
        try {
            $board = new Board();
            $board->title = $request->title;
            $board->policy = $request->policy;
            $board->save();
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
                $cards = Card::where('list_id',$list->id)->get();
                foreach ($cards as $card){
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

}
