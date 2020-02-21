@extends('layouts.default');

@section('content')
    <div class="test">
        @foreach($selection as $elem)
            <p>{{$elem}}</p>
        @endforeach
    </div>

@stop
