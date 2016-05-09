<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

use App\Http\Requests;

use JWTAuth;
use Tymon\JWTAuth\Exceptions\JWTException;

use App\User;

class AccountController extends Controller
{
    public function index(Request $request)
    {
      if( ! $user = JWTAuth::parseToken()->authenticate() )
      {
        return response()->json(['error' => 'invalid_token', 422]);
      }

      return response()->json(compact('user'),200);
    }

    public function edit(Request $request)
    {
      if( ! $user = JWTAuth::parseToken()->authenticate() )
      {
        return response()->json(['error' => 'invalid_token', 422]);
      }

      $edit = User::where('id',$user->id)->first();

      if($request->name)$edit->name = $request->name;
      if($request->email)$edit->email = $request->email;
      if($request->password && $request->old_password)
      {
        if(!\Hash::check($request->old_password, $user->password))
        {
          return response()->json(['error' => 'invalid_credentials'], 422);
        }
        $edit->password = \Hash::make($request->password);
      }

      $edit->save();

      return response()->json(compact('edit'), 200);
    }
}
