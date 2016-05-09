<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

use JWTAuth;
use Tymon\JWTAuth\Exceptions\JWTException;

use App\Http\Requests;

use App\User;

class AuthenticateController extends Controller
{
    public function login(Request $request)
    {
      $this->validate($request,[
        'email'     => 'required|email',
        'password'  => 'required|min:6',
      ]);

      $user = User::where('email', $request->email)->first();

      if(empty($user))
      {
        return response()->json(['error' => 'invalid_credentials'], 422);
      }

      if(!\Hash::check($request->password, $user->password))
      {
        return response()->json(['error' => 'invalid_credentials'], 422);
      }

      $token = JWTAuth::fromUser($user);

      return response()->json($token, 200);
    }

    public function register(Request $request)
    {
      $this->validate($request,[
        'name'    => 'required|min:4',
        'email'   => 'required|email|unique:users',
        'password'=> 'required|min:6',
      ]);

      $user = new User;

      $user->name = $request->name;
      $user->email = $request->email;
      $user->password = \Hash::make($request->password);

      $user->save();

      return response()->json(['success' => 'user_created'], 200);
    }
}
