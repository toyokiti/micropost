<?php

namespace App;

use Illuminate\Notifications\Notifiable;
use Illuminate\Foundation\Auth\User as Authenticatable;

class User extends Authenticatable
{
    use Notifiable;

    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $fillable = [
        'name', 'email', 'password',
    ];

    /**
     * The attributes that should be hidden for arrays.
     *
     * @var array
     */
    protected $hidden = [
        'password', 'remember_token',
    ];
    
    public function microposts()
    {
        return $this->hasMany(Micropost::class);
    }
    
    public function followings()
    {
        return $this->belongsToMany(User::class, 'user_follow', 'user_id', 'follow_id')->withTimestamps();
    }
    
    public function followers()
    {
        return $this->belongsToMany(User::class, 'user_follow', 'follow_id', 'user_id')->withTimestamps();
    }
    
    // 機能:フォローしているUser達を取得する。
    public function follow($userId)
    {
        // 既にフォローしているかの確認
        $exist = $this -> is_following($userId);
        // 相手が自分自身ではないかの確認
        $its_me = $this->id == $userId;
        
        if($exist || $its_me)
        {
            // 既にフォローしていたら何もしない
            return true;
        }else{
            // 未フォローであればフォローする
            $this->followings()->attach($userId);
            return true;
        }
    }
    
    // 機能：フォローされているUser達を取得する。
    public function unfollow($userId)
    {
        // 既にフォローしているかの確認
        $exist = $this->is_following($userId);
        // 相手が自分自身でないかの確認
        $its_me = $this->id == $userId;
        
        if($exist && !$its_me )
        {
            // すでにフォローしていればフォローを外す
            $this->followings()->detach($userId);
            return true;
        }else{
            // 未フォローであれば何もしない
            return false;
        }
    }
    
    // 機能：フォローしているか/していないか をtrue/falseで返す。
    public function is_following($userId)
    {
        // true or false を返す
        return $this->followings()->where('follow_id' , $userId)->exists();
    }
    
    // 機能：タイムライン用のマイクロポストを取得する
    public function feed_microposts()
    {
        $follow_user_ids = $this->followings()->pluck('users.id')->toArray();
        $follow_user_ids[] = $this->id;
        return Micropost::whereIn('user_id', $follow_user_ids);
    }
    
    // micropostモデルとの関係を定義(多対多)
    public function favorites()
    {
        return $this->belongsToMany(Micropost::class, 'favorites', 'user_id', 'micropost_id')->withTimestamps();
    }
    
    // 機能：ツイートをお気に入りにする。
    public function getFavoriteTweet($micropostId)
    {
        // 既にお気に入りか確認する。
        $alreadyFavorite =  $this->is_favorite($micropostId);
        
        if($alreadyFavorite){
            // true(すでにお気に入り)ならば何もしない
            return false;
        } else {
            // false(notお気に入り)ならばお気に入りにする。
            $this->favorites()->attach($micropostId);
            return true;
        }
    }
    
    // 機能：お気に入りのツイートを解除する。
    public function cancelFavoriteTweet($micropostId)
    {
        
        // 既にお気に入りか確認する。
        $alreadyFavorite = $this->is_favorite($micropostId);
        
        if($alreadyFavorite){
            // true(すでにお気に入り)ならば、お気に入りを解除する。
            $this->favorites()->detach($micropostId);
            return true;
        } else {
            // false(お気に入りでない)ならば、何もしない。
            return false;
        }
        
        
    }
    
    // 機能：すでにお気に入りかどうか判断を行う。
    // お気に入りの場合：true/そうでない場合：false
    public function is_favorite($micropostId)
    {
        return $this->favorites()->where('micropost_id', $micropostId)->exists();
    }

}
