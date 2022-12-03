@extends('app')

@section('content')

    <div class="content">
        <div class="title">Hello, you can contact me with</div>
        <div class="text">Escaped: {{ $email  }}</div>
        <div class="text">Unescaped: {!! $email !!}</div>
    </div>

@stop
