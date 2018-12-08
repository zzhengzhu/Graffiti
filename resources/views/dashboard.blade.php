@extends('layouts.app')

@section('content')
<div class="container mt-2">
    <div class="row justify-content-center">
        <div class="col-md-8">
            <div class="card">
                <div class="card-header">Dashboard</div>

                <div class="card-body">
                    @if (session('status'))
                        <div class="alert alert-success" role="alert">
                            {{ session('status') }}
                        </div>
                    @endif

                    You are logged in! <br />
                    For report bugs, you can either make a post to point to any admin-created posts 
                    or email: zuoperation@gmail.com <br />
                    <br/>
                    The Post button will let you create a post based on your location<br/>
                    The Pin button will create a Pinpoint based on where you click on the map<br/>
                    Your can point to a Post or a Pin when you create a Post, 
                    and then the markers will be linked together<br/>
                    The radius of a Post or a Pin determines the area (the green aura) 
                    that it can be discovered on the map.<br/>
                    Althrough some posts are out of sight, 
                    if they are linked with any in-sight Posts/Pinpoints, 
                    they will also be revealed in the map. <br/>
                    Upvote other's posts to gain energy <br/>

                    Feel free to explore the website around. Good Luck and Have Fun! <br />
                    Click "Home" in the navbar to visit game interface <br />
                </div>
            </div>
        </div>
    </div>
</div>
@endsection
