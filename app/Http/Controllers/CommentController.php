<?php

namespace App\Http\Controllers;

use App\Models\Comment;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class CommentController extends Controller
{
    public function comment(Request $request)
    {
        try {
            $comment = new Comment();
            $comment->user_id = Auth::user()->id;
            $comment->card_id = $request->card_id;
            $comment->content = $request->contents;
            $comment->save();
            $data = [
                'status' => 'success',
            ];
        } catch (\Exception $exception) {
            $data = [
                'data' => 'error',
                'message' => $exception,
            ];
        }
        return response()->json($data);
    }
}
