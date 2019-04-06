@if(Auth::user()->is_favorite($micropost->id))
    {!! Form::open() !!}
    
@else

@endif