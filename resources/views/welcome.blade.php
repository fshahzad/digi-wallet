@extends('layouts.app')

@section('contents')
    <div id="app"></div>
    <div class="container">
        <hr>
        <p>This is a sample application. On application load it logs in a random user and fetches the current user data from the API.</p>
        <p>Current User:</p>
        <code style="background: #f4f4f4; padding: 10px; display: block; max-width: 75%;">
            {{ request()->user() ?? '' }}
        </code>
    </div>
@endsection
