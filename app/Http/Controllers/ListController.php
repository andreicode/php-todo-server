<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

use JWTAuth;
use Tymon\JWTAuth\Exceptions\JWTException;

use App\Http\Requests;
use App\Item;

class ListController extends Controller
{
    public function index()
    {
      if( ! $user = JWTAuth::parseToken()->authenticate() )
      {
        return response()->json(['error' => 'invalid_token', 422]);
      }

      $list = Item::where('user_id', $user->id)->orderBy('created_at','desc')->get();

      return response()->json(compact('list'), 200);
    }

    public function store(Request $request)
    {
      if( ! $user = JWTAuth::parseToken()->authenticate() )
      {
        return response()->json(['error' => 'invalid_token', 422]);
      }

      $this->validate($request,[
        'name' => 'required',
        'expires_at' => 'date'
      ]);

      $item = new Item;
      $item->user_id = $user->id;
      $item->name = $request->name;

      if($request->expires_at)$item->expires_at = $request->expires_at;

      $item->save();

      return response()->json(compact('item'), 200);
    }

    public function edit(Request $request)
    {
      if( ! $user = JWTAuth::parseToken()->authenticate() )
      {
        return response()->json(['error' => 'invalid_token', 422]);
      }

      $item = Item::where('id', $request->id)->first();

      if(empty($item))
      {
        return response()->json(['error' => 'item_not_found'], 404);
      }

      if($item->user_id != $user->id)
      {
        return response()->json(['error' => 'not_owner'], 404);
      }

      if($request->name)$item->name = $request->name;
      if($request->expires_at)$item->expires_at = $request->expires_at;

      $item->save();

      return response()->json(compact('item'), 200);
    }

    public function delete(Request $request)
    {
      if( ! $user = JWTAuth::parseToken()->authenticate() )
      {
        return response()->json(['error' => 'invalid_token', 422]);
      }

      $item = Item::where('id', $request->id)->first();

      if(empty($item))
      {
        return response()->json(['error' => 'item_not_found'], 404);
      }

      if($item->user_id != $user->id)
      {
        return response()->json(['error' => 'not_owner'], 404);
      }

      $item->delete();

      return response()->json(['success' => 'item_deleted'], 200);
    }
}
