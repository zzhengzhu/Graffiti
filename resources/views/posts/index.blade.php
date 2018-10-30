@extends('layouts.app')

@section('content')
    <div class="container">
        <h1 class="pt-2">My Posts</h1>
        @if(count($posts) > 0)
            @foreach ($posts as $post)
                <div class="well p-2">
                    <h3>{{$post->content}}</h3>
                    <div class="row">
                        <form method="post" action="{{route('posts.destroy', ['post' => $post->id])}}">
                            @method('DELETE')
                            @csrf
                            <button type="submit" class="btn btn-dark btn-sm">Delete</button>
                        </form>
                        <p id="lol" hidden>{{json_encode($post->location)}}</p>
                        <a href="{{route('pages.index', ['postjson' => json_encode($post->location)])}}" class="btn btn-dark btn-sm ml-2">Go To</a>
                    </div>
                </div>
            @endforeach
        @else
            <p>No posts found</p>
        @endif
    </div>
@endsection