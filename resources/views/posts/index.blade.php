@extends('layouts.app')

@section('content')
    <div class="container">
        <h1 class="pt-2">My Posts</h1>
        @if(count($posts) > 0)
            @foreach ($posts as $post)
                <div class="container mb-2">
                    <div class="row" style="width:80%;height:80%;">
                        <p class="m-0">{{$post->content}}</p>
                        @if($post->link)
                        <img style="width:100%;height:100%" 
                         src="{{$post->link}}"></img>
                        @endif
                    </div>
                    
                    <div class="row">
                        <p class="m-0">Radius: {{$post->radius}}</p>
                        <p class="m-0 pl-2">Created Time: {{$post->created_at}}</p>
                        <form class="pl-2" method="post" action="{{route('posts.destroy', ['id' => $post->id])}}">
                            @method('DELETE')
                            @csrf
                            <button type="submit" class="btn btn-dark btn-sm">Delete</button>
                        </form>
                        <a href="{{route('pages.index', ['postjson' => json_encode($post->location)])}}" class="btn btn-dark btn-sm ml-2">Go To</a>
                    </div>
                </div>
            @endforeach
        @else
            <p>No posts found</p>
        @endif
        
        <h1 class="pt-2">My Pinpoints</h1>
        @if(count($pinpoints) > 0)
            @foreach ($pinpoints as $pinpoint)
                <div class="container mb-2">
                    <div class="row" style="width:80%;height:80%">
                        <p class="m-0">{{$pinpoint->content}}</p>
                        @if($pinpoint->link)
                        <img style="width:100%;height:100%" 
                        src="{{$pinpoint->link}}"></img>
                        @endif
                    </div>
                    
                    <div class="row">
                        <p class="m-0">Radius: {{$pinpoint->radius}}</p>
                        <p class="m-0 pl-2">Created Time: {{$pinpoint->created_at}}</p>
                        <form class="pl-2" method="post" action="{{route('pinpoints.destroy', ['id' => $pinpoint->id])}}">
                            @method('DELETE')
                            @csrf
                            <button type="submit" class="btn btn-dark btn-sm">Delete</button>
                        </form>
                        <a href="{{route('pages.index', ['postjson' => json_encode($pinpoint->location)])}}" class="btn btn-dark btn-sm ml-2">Go To</a>
                    </div>
                </div>
            @endforeach
        @else
            <p>No pinpoints found</p>
        @endif
    </div>
@endsection