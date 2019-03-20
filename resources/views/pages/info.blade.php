@extends('layouts.app')

@section('content')
<div class="container mt-2">
    <div class="row justify-content-center">
        <div class="col-md-8">
            <div class="card">
                <h2 class="card-header">Welcome to Graffiti</h2>

                <div class="card-body">
                    <p>Graffiti is a Volunteer Geographic Information interface 
                        for location-based communication, which is the outcome of my Honours Thesis
                        "The Design of a Location-based Neighborhood Game 
                        that Stimulates the Generation of Social Capital". 
                        The base game is a location-based social media that generates social captial
                         through local online communication. 
                         Some game mechanics are applied to motivate participations using Self-Determination Theory. 
                          The interface was renamed as <b>COMM</b> in a recent update. 
                         <b>Station</b> is a hosted interface for creating and sharing "transit routes" 
                          that link similar places of interest together in Waterloo Region.
                    </p>
                    <a href="/tutorial">Go to Tutorial for more information</a><br />
                    Click "Login" or "Register" to get access to your game account<br /> 
                    USE FRESH, NEVER-USED PASSWORD FOR THE GAME ACCOUNT<br />
                    IT IS HIGH RISK TO USE THE SAME PASSWORD AS THE PASSWORD USED IN YOUR OTHER ACCOUNTS!<br />
                </div>
            </div>
        </div>
    </div>
</div>
@endsection