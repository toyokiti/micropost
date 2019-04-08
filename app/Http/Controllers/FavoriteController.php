<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

class FavoriteController extends Controller
{
    
    // お気に入りに追加する。
    public function store(Request $request, $id)
    {
        \Auth::user()->getFavoriteTweet($id);
        return back();
    }
    
    public function destroy(Request $request, $id)
    {
        \Auth::user()->cancelFavoriteTweet($id);
        return back();
    }
}
