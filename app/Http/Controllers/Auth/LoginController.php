<?php

namespace App\Http\Controllers\Auth;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;


class LoginController extends Controller
{
    public function login(Request $request)
    {
        if (\Auth::attempt(['email' => $request->email, 'password' => $request->password])) {
            $token = \Auth::user()->createToken('AccessToken')->plainTextToken;
            return response()->json(['token' => $token], 200);
        } else {
            return response()->json(['error' => '認証に失敗しました。'], 401);
        }
    }

    public function user(Request $request)
    {
        if ($request->user()->currentAccessToken()) {
            return response()->json(
                [
                    $request->user()->name,
                    $request->user()->email,
                ],
                200
            );
        } else {
            return response()->json(['error' => 'ログインしていません。'], 401);
        }
    }

    public function logout(Request $request)
    {
        if ($request->user()->currentAccessToken()) {
            $request->user()->currentAccessToken()->delete();
            return response()->json(['message' => 'ログアウトしました。'], 200);
        } else {
            return response()->json(['error' => 'ログインしていないので、ログアウトできませんでした。'], 401);
        }
    }
}
