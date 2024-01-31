@extends('layouts.app')

@section('content')
    <div id="app">
        <chat-component :current-user="{{ json_encode(Auth::user()) }}"></chat-component>
    </div>
@endsection
