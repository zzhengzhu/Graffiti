@extends('layouts.app')

@section('content')
<div class="container mt-2">
    <div class="row justify-content-center">
        <div class="col-md-12">
            <div class="row mb-4">
                <div class="card col-sm m-3">
                    <div class="card-body">
                        <h5 class="card-title">Tutorial for <b>COMM</b></h5>
                        <p class="card-text">COMM is a location-based communication platform 
                                integrated with gamification mechanics. 
                                In COMM, you can create posts on a map, 
                                pin a message and upvote your favorite posts to let more people see it. 
                        </p>
                        <p> You will need an account to use COMM</p>
                        <a href="/tutorial/comm" class="btn btn-primary">Go to tutorial</a>
                    </div>
                </div>
                <div class="card col-sm m-3">
                    <div class="card-body">
                        <h5 class="card-title">Tutorial for <b>Station</b></h5>
                        <p class="card-text">
                            Station is a Volunter Geographic Information platform 
                            for discovering and sharing places of interest in Waterloo Region. 
                            Users create "stations" and link locations with similar feature like "places for having fun" or "bubble teas"
                            together, which creates imaginary transit maps. 
                        </p>
                        <p> You do not need an account to use Station</p>
                        <a href="/tutorial/station" class="btn btn-primary">Go to tutorial</a>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection