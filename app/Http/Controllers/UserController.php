<?php

namespace App\Http\Controllers;

use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Validator;


class UserController extends Controller
{
    public function __construct()
    {
        $this->middleware('auth:api', ['except' => ['login', 'register']]);
    }

    public function login(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'email' => 'required|email',
            'password' => 'required',
        ]);

        if ($validator->fails()) {
            return response()->json($validator->errors(), 422);
        }

        if (!$token = auth()->attempt($validator->validated())) {
            return response()->json(['error' => 'Unauthorized', 'status' => 401]);
        }

        return $this->createNewToken($token);
    }

    public function register(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'name' => 'required|string|between:2,100',
            'email' => 'required|string|email|max:100|unique:users',
            'password' => 'required|string|min:6',
        ]);

        if ($validator->fails()) {
            return response()->json($validator->errors()->toJson(), 400);
        }

        $user = User::create(array_merge(
            $validator->validated(),
            ['password' => bcrypt($request->password),
            'avatar' => 'https://firebasestorage.googleapis.com/v0/b/trello-eb91c.appspot.com/o/RoomsImages%2F1639542019135?alt=media&token=6a53a8a9-a60c-43a2-89b6-60b323c0678a'],
        ));

        return response()->json([
            'message' => 'User successfully registered',
            'user' => $user
        ], 201);
    }

    public function logout()
    {
        auth()->logout();

        return response()->json(['message' => 'User successfully signed out']);
    }

    public function refresh()
    {
        return $this->createNewToken(auth()->refresh());
    }

    public function userProfile()
    {
        return response()->json(auth()->user());
    }

    protected function createNewToken($token)
    {
        return response()->json([
            'access_token' => $token,
            'token_type' => 'bearer',
            'status' => 200,
            'expires_in' => auth()->factory()->getTTL() * 60,
            'user' => auth()->user()
        ]);
    }

    public function changePassWord(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'old_password' => 'required|string|min:6',
            'new_password' => 'required|string|confirmed|min:6',
        ]);

        if ($validator->fails()) {
            return response()->json([
                'message' => 'Sai định dạng',
            ], 401);
        }

        $userId = auth()->user()->id;
        $user = User::findOrFail($userId);

        if (!password_verify($request->old_password, $user->password)) {
            return response()->json([
                'message' => 'Mật khẩu cũ không khớp',

            ], 401);
        }

        $user = User::where('id', $userId)->update(
            ['password' => bcrypt($request->new_password)]
        );

        return response()->json([
            'message' => 'Đổi mật khẩu thành công',
            'user' => $user,
        ], 201);
    }

    public function getAvatar()
    {
        try {
            $avatar = Auth::user()->avatar;
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

    public function updateAvatar(Request $request)
    {
        try {
            $id = Auth::user()->id;
            $user = User::findOrFail($id);
            $user->avatar = $request->avatar;
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

    public function searchByEmail(Request $request){
        try {
            $key = $request->input('key');
            $user = User::where('email', $key)->get();
            return response()->json([
                'message' => 'tìm kiếm thanh công',
                'httpCode' => 200,
                'data' => $user
            ]);
        } catch (\Exception $exception) {
            return response()->json([
                'message' => 'lỗi hệ thống',
                'httpCode' => $exception->getCode()
            ]);
        }
    }

}
