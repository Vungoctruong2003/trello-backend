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
            $comment->content = $request->contentsCmt;
            $comment->save();
            $comment = Comment::orderBy('id','DESC')->with('user')->limit(1)->get();

            $data = [
                'status' => 'success',
                'data' => $comment
            ];
        } catch (\Exception $exception) {
            $data = [
                'status' => 'error',
                'data' => $exception,
            ];
        }
        return response()->json($data);
    }

    public function delete($id)
    {
        try {
            $comment = Comment::findOrFail($id);
            if ($comment->user_id == Auth::user()->id) {
                $comment->delete();
                $data = [
                    'status' => 'success',
                    'message' => "Xoá bình luận thành công",
                    "cmtRemove" => $comment
                ];
            }

        } catch (\Exception $exception) {
            $data = [
                'data' => 'error',
                'message' => "Bạn không xoá được bình luận này",
            ];
        }
        return response()->json($data);
    }

}
