@extends('layouts.app')

@section('content')
    <div id="app">
        <chat-component :current-user="{{ json_encode(Auth::user()) }}"></chat-component>
    </div>

<!--    <script>
        window.user_id = {{ auth()->id() }};
        window.user_name = "{{ auth()->user()->name }}"
    </script>-->
@endsection
