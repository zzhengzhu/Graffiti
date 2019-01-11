@extends('layouts.app')

@section('content')
<div class="container mt-2">
    <div class="row justify-content-center">
        <div class="col-md-12">
            <div class="card">
                <h3 class="card-header">Tutorial</h3>
                <div class="container">
                    <div class="row mb-4">
                        <div class="col-sm">
                            <p class="m-0 font-weight-bold">Create a Post:</p>
                            <p class="m-1">Click "Post", and then input contents and an optional image url 
                                (Sample url: https://i.imgur.com/1XgBtTH.jpg), and then click "Submit"</p>
                            <p class="m-1">The default radius of a Post is 100 m. 
                                Creating a Post will consume 100 E and generate 50 EXP. <p>
                        </div>
                        <div class="col-sm">
                            <img src="images/createpo.jpg" class="img-fluid">
                        </div>
                    </div>
                    <div class="row mb-4">
                        <div class="col-sm">
                            <p class="m-0 font-weight-bold">Create a Pinpoint:</p>
                            <p class="m-1">1: Click on any location on the map, 
                                and a pulse marker will show up on your clicked location. 
                                The location is where your pinpoint will be located. 
                            <p class="m-1">2: Click "Pin", and then input contents, 
                                an optional image url and the visible radius of the pinpoint (1m <= r <= 10000m), 
                                and then click "Submit"</p>
                            <p class="m-1">Creating a Pinpoint will consume 50 E and generate 25 EXP. 
                                the corresponding Post's radius will increase. </p>
                        </div>
                        <div class="col-sm">
                            <img src="images/pplocation.jpg" class="img-fluid">
                        </div>
                        <div class="col-sm">
                            <img src="images/createpp.jpg" class="img-fluid">
                        </div>
                    </div>
                    <div class="row mb-4">
                        <div class="col-sm">
                            <p class="m-0 font-weight-bold">Upvote:</p>
                            <p class="m-1">1: Click on a Post on the map, 
                                the content of the Post will show up. 
                                All related Posts, Pinpoints and their connections will show up on the map.
                            <p class="m-1">2: Click "Upvote" in the popup, and 
                                the corresponding Post's radius will increase. </p>
                            <p class="m-1">The increment of radius will slow down as the radius increases.
                                You will regain 10 E when upvoting other's posts 
                                and consume 10 E when upvoting your own posts. 
                                You generate 5 EXP when upvoting your own posts. 
                                </p> 
                        </div>
                        <div class="col-sm">
                            <img src="images/upvote.jpg" class="img-fluid">
                        </div>
                    </div>
                    <div class="row mb-4">
                        <div class="col-sm">
                            <p class="m-0 font-weight-bold">Linking:</p>
                            <p class="m-1">1: Click on a Post or Pinpoint on the map, 
                                you will notice a blue pulsing arua around the Post or Pinpoint that you clicked.
                                The blue arua indicates that the post or pinpoint has been selected. 
                            <p class="m-1">2: It is the same step as creating a Post. 
                                However, you will find a notice in the pop out dialog, 
                                which indicates that a post or a pinpoint is selected. 
                                You can click "Cancel Selection" to cancel your selection. 
                                </p>
                            <p class="m-1">Linking will refer markers with each other, 
                                so some posts or pinpoints will be discovered even outside your sight. 
                                </p> 
                        </div>
                        <div class="col-sm">
                            <img src="images/pointto.jpg" class="img-fluid">
                        </div>
                        <div class="col-sm">
                            <img src="images/cancellink.jpg" class="img-fluid">
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection