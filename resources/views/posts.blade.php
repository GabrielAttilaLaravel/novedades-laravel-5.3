@extends('layouts.app')

@section('content')
    <ul>
        @foreach($posts as $post)
            <li>{{ $post->title }} ({{ $post->user->name }})</li>
        @endforeach
    </ul>

    {{ $posts->render() }}
@endsection