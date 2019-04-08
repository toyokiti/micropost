@extends('layouts.app')

@section('content')
    <div class="row">
        <aside class="col-sm-4">
            @include('users.card', ['user' => $user])
        </aside>
        <div class="col-sm-8">
            @include('users.navtabs', ['user' => $user])
            <!--　お気に入りツイートを読み込む　-->
            @include('microposts.microposts', ['user' => $user, 'microposts' => $favorites])
        </div>
    </div>
@endsection