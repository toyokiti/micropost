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
    
    // public function destroy($id)
    // {
    //     $micropost = \App\Micropost::find($id);

    //     if (\Auth::id() === $micropost->user_id) {
    //         $micropost->delete();
    //     }

    //     return back();
    // }
}
    