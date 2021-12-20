<?php

namespace App\Http\Controllers;

use App\Models\Tag;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class TagController extends Controller
{
    public function embark($id)
    {
        $tag = Tag::where('user_id', Auth::user()->id)->where('card_id', $id)->get();
        if ($tag->isEmpty()) {
            try {
                $tag = new Tag();
                $tag->user_id = Auth::user()->id;
                $tag->card_id = $id;
                $tag->save();
                $data = [
                    'status' => 'success',
                ];
            } catch (\Exception $exception) {
                $data = [
                    'data' => 'success',
                    'message' => $exception,
                ];
            }
        } else {
            $data = [
                'data' => 'success',
                'message' => 'Bạn đã tham gia thẻ này rồi',
            ];
        }

        return response()->json($data);
    }

    public function addMember(Request $request)
    {
        try {

            $id = User::where('email', $request->email)->get()->id;
            $tag = new Tag();
            $tag->user_id = $id;
            $tag->card_id = $request->card_id;
            $tag->save();
            $data = [
                'status' => 'success',
            ];
        } catch (\Exception $exception) {
            $data = [
                'data' => 'success',
                'message' => $exception,
            ];
        }
        return response()->json($data);
    }


}
