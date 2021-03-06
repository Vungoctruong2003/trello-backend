<?php

namespace App\Http\Controllers;

use App\Models\Card;
use App\Models\Comment;
use App\Models\Tag;
use Illuminate\Http\Request;

class CardController extends Controller
{
    public function store(Request $request)
    {
        try {
            $card = new Card();
            $card->list_id = $request->list_id;
            $card->title = $request->title;
            $card->content = $request->contents;
            $seq = Card::where('list_id', $request->list_id)->count();
            $card->seq = $seq;
            $card->save();
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

    public function update(Request $request)
    {
        try {
            $card = Card::findOrFail($request->id);
            $card->title = $request->title;
            $card->content = $request->contents;
            $card->save();
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
            $cards = Card::where('id', $id)->get();
            foreach ($cards as $card) {
                Comment::where('card_id', $card->id)->delete();
                Tag::where('card_id', $card->id)->delete();
            }
            Card::destroy($card->id);
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
            $data = $request->cards;
            foreach ($data as $cards) {
                foreach ($cards as $card) {
                    $oldCard = Card::findOrFail($card['id']);
                    $oldCard->list_id = $card['list_id'];
                    $oldCard->seq = $card['seq'];
                    $oldCard->save();
                }
            }
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

    public function index($id)
    {
        try {
            $card = Card::findOrFail($id);
            $comments = Comment::where('card_id', $id)->with('user')->get();
            $tag = Tag::where('card_id', $id)->with('user')->get();
            $data = [
                'status' => 'success',
                'comments' => $comments,
                'tags' => $tag,
                'card' => $card,
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
