<?php

namespace App\Http\Controllers;

use App\Models\Card;
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

    public function delete($id)
    {
        try {
            Card::destroy($id);
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

    public function getById($id){
        try {
            $card = Card::findOrFail($id);
            $data = [
                'status' => 'success',
                'data' => $card
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
