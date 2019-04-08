<?php

/*
|--------------------------------------------------------------------------
| Web Routes
|--------------------------------------------------------------------------
|
| Here is where you can register web routes for your application. These
| routes are loaded by the RouteServiceProvider within a group which
| contains the "web" middleware group. Now create something great!
|
*/

Route::get('/', 'MicropostsController@index');


// ユーザ登録
Route::get('signup', 'Auth\RegisterController@showRegistrationForm')->name('signup.get');
Route::post('signup', 'Auth\RegisterController@register')->name('signup.post');

// ユーザ機能
Route::group(['middleware' => ['auth']], function () {
    Route::resource('users', 'UsersController', ['only' => ['index', 'show']]);
    
    Route::group(['prefix' => 'users/{id}'], function(){
        // フォローアンフォローのルーティング
        Route::post('follow','UserFollowController@store')->name('user.follow');        
        Route::delete('unfollow','UserFollowController@destroy')->name('user.unfollow');
        Route::get('followings','UsersController@followings')->name('users.followings');
        Route::get('followers','UsersController@followers')->name('users.followers');
        // お気に入り機能のルーティング(一覧の取得)
        Route::get('favorites','UsersController@favorites')->name('users.favorites');
 
    });
 
    // お気に入り/お気に入り解除のルーティング
    Route::group(['prefix' => 'microposts/{id}'], function(){
        Route::post('favorite', 'FavoriteController@store')->name('user.favorite');
        Route::delete('unfavorite','FavoriteController@destroy')->name('user.unfavorite');
    });    
        
    Route::resource('microposts', 'MicropostsController',['only'=>['store','destroy']]);
});

// ログイン認証
Route::get('login', 'Auth\LoginController@showLoginForm')->name('login');
Route::post('login', 'Auth\LoginController@login')->name('login.post');
Route::get('logout', 'Auth\LoginController@logout')->name('logout.get');