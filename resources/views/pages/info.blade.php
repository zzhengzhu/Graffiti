@extends('layouts.app')

@section('content')
<div style="height: 100%;
        width: 100%; position: relative; text-align: center;overflow:hidden;">
    <div class="" style="
    background-image: url('/images/background.png');
    background-position: center;
    background-repeat: no-repeat;
    background-size: cover;
    height:100%;
    position: relative;
    ">
        <h1 style="position: absolute;top: 50%; 
        left: 50%; 
        transform: translate(-50%, -50%);
        color:cornsilk">
            Graffiti<br/>
            <br/>
            a Location-based Game
        </h1>
    </div> 
</div>
<div style="height: 100%;
        width: 100%; position: relative; text-align: center;overflow:hidden;">

    <div style="position: absolute;top: 50%; 
    left: 50%; 
    transform: translate(-50%, -50%);
    ">
        Graffiti is a Volunteer Geographic Information interface 
        for location-based communication 
        (<a href="https://github.com/zzhengzhu/Graffiti-open-data-archive">https://github.com/zzhengzhu/Graffiti-open-data-archive</a>). 
        <br/><br/>
        <a class="btn btn-sm btn-dark" href="/tutorial">Go to Tutorial</a>
        <br/><br/>
        <a href="/login">Login</a> or <a href="/register">Register</a> to access your game account
        <br/><br/>
        Important notice: 
        <br/>
        USE FRESH, NEVER-USED PASSWORD FOR THE GAME ACCOUNT<br />
        IT IS HIGH RISK TO USE THE SAME PASSWORD AS THE PASSWORD USED IN YOUR OTHER ACCOUNTS!<br />
    </div>

</div>
@endsection