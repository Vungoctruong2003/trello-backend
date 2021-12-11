<?php

namespace App\Http\Controllers;

use App\Models\User;
use Illuminate\Http\Request;

class UserController extends Controller
{
    public function getAvatar($id)
    {
        try {
            $user = User::findOrFail($id);
            $avatar = $user->avatar;
            $data = [
                'status' => 'success',
                'data' => $avatar
            ];
        } catch (\Exception $exception) {
            $data = [
                'status' => 'error',
                'message' => $exception
            ];
        }
        return response()->json($data);
    }

    public function updateAvatar(Request $request, $id)
    {
        try {
            $user = User::findOrFail($id);
            $user->image = $request->image;
            $user->save();
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
