@extends('layouts.app')

@section('content')
    @if (count($logs) > 0) 
        @foreach ($logs as $log)
        <div class="well">
            <div class="btn btn-dark col-sm-12" data-toggle="collapse" href="#update{{$log->id}}" 
            role="button" aria-expanded="false" aria-controls="update{{$log->id}}">
                <h3>{{$log->title}}</h3>
            <small>Created by {{$log->user_name}} at {{$log->created_at}}</small>
            </div>
            <div class="collapse" id="update{{$log->id}}">
                <div class="card card-body">
                    {{$log->content}}
                </div>
            </div>
        </div>
        @endforeach
    @else
        <p>No logs found</p>
    @endif 

    @if ($root == 0) 
    @else
        <h3 style="margin-top: 5px;">Root user:</h3>
        <a class="btn btn-dark" href="/updates/create">Create a log</a>
    @endif
@endsection