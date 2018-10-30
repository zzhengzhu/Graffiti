@extends('layouts.app')

@section('content')
    <h1>Create a update log</h1>
    <form method="post" action="{{route('updates.store')}}">
        @csrf
        <div class="form-group">
            <label>Title</label>
            <input type="text" class="form-control" name="title" placeholder="Enter Title">
        </div>
        <div class="form-group">
            <label>Contents</label>
            <textarea class="form-control" name="content" rows="20"></textarea>
        </div>
        <button type="submit" class="btn btn-dark">Submit</button>
    </form>
@endsection