@if(Auth::user()->is_favorite($micropost->id))
   <!--お気に入りを解除する。-->
    {!! Form::open(['route' => ['user.unfavorite', $micropost->id], 'method' => 'delete']) !!}
        {!! Form::submit('Unfavorite', ['class' => "btn btn-danger btn-block btn-sm"]) !!}
    {!! Form::close() !!}
@else
    {!! Form::open(['route' =>['user.favorite', $micropost->id]]) !!}
        {!! Form::submit('Favorite',['class' => "btn btn-primary btn-block btn-sm"]) !!}
    {!! Form::close() !!}
@endif