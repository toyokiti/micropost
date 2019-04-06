<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Micropost extends Model
{
    //
    protected $fillable = ['content', 'user_id'];
    
    public function user()
    {
        return $this->belongsTo(User::class);
    }


  public function store(Request $request)
    {
        $this->validate($request, [
            'content' => 'required|max:191',
        ]);

        $request->user()->microposts()->create([
            'content' => $request->content,
        ]);

        return back();
    }
    
    // userモデルとの関係を定義(多対多)
    public function favoriteIntermediateTable()
    {
        return $this->belongsToMany(User::class,'favorites','micropost_id','user_id');
    }
    
}
    