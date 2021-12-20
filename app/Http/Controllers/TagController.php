<?php

namespace App\Http\Controllers;

use App\Models\Tag;
use Illuminate\Http\Request;

class TagController extends Controller
{
    public function addMember(Request $request)
    {
        try {
            $user = Tag::where('user_id', $request->user_id)->where('card_id', $request->card_id)->get();
            if ($user->isEmpty()) {
                $tag = new Tag();
                $tag->user_id = $request->user_id;
                $tag->card_id = $request->card_id;
                $tag->save();
                $data = [
                    'status' => 'add',
                ];
            } else {
                Tag::where('user_id', $request->user_id)->where('card_id', $request->card_id)->delete();
                $data = [
                    'status' => 'delete',
                ];
            }

        } catch (\Exception $exception) {
            $data = [
                'data' => 'success',
                'message' => $exception,
            ];
        }
        return response()->json($data);
    }

}
