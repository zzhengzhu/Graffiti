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
                    This is a beta test version of the amazing LBS game Graffiti. <br />
                    For report bugs, you can either make a post to point to Admin Pinpoint
                    or email: zuoperation@gmail.com <br />

                    Feel free to explore the website around. Good Luck and Have Fun! <br />
                    Click on "Home" to go to the game interface <br />
                </div>
            </div>
        </div>
    </div>
</div>
@endsection
