@extends('layouts.app')

@section('content')
<div class="container mt-2">
    <div class="row justify-content-center">
        <div class="col-md-8">
            <div class="card">
                <h2 class="card-header">Welcome to Graffiti</h2>

                <div class="card-body">
                    <div>Graffiti is a Volunteer Geographic Information interface 
                        for location-based communication, which is the outcome of my Honours Thesis
                        "The Design of a Location-based Neighborhood Game 
                        that Stimulates the Generation of Social Capital" 
                        (<a href="https://github.com/eralogos/Graffiti-open-data-archive">https://github.com/eralogos/Graffiti-open-data-archive"</a>). 
                        <br />
                        The base game named <b>COMM</b> is a location-based social medium that generates social captial
                         through local online communication. 
                         Some game mechanics are applied to motivate participations using Self-Determination Theory. 
                         <br />
                         <b>Station</b> is a hosted interface for creating and sharing "transit routes" 
                          that link similar places of interest together in Waterloo Region.
                    </div>
                    <a href="/tutorial">Go to Tutorial for more information</a><br />
                    Click "Login" or "Register" to get access to your game account<br />
                    <br />
                    Important notice: 
                    <br />
                    USE FRESH, NEVER-USED PASSWORD FOR THE GAME ACCOUNT<br />
                    IT IS HIGH RISK TO USE THE SAME PASSWORD AS THE PASSWORD USED IN YOUR OTHER ACCOUNTS!<br />
                    Toxic and offensive posts will be deleted by admin<br /> 
                </div>
            </div>
        </div>
    </div>
</div>
@endsection