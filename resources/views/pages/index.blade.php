@extends('layouts.app')

@section('content')
    <script>
        var urlParams = new URLSearchParams(window.location.search);
        postjson = JSON.parse(urlParams.get('postjson'));
        console.log(postjson);
    </script>
    <div id="mapframe">
        <div id="hoverbar" class="container">
            <div class="row col-sm-12">
                <!-- Button trigger modal -->
                <button type="button" class="btn btn-dark" data-toggle="modal" data-target="#sendPost">
                    Post
                </button>
                <button type="button" class="btn btn-dark ml-2" data-toggle="modal" data-target="#pinPoint">
                    Pinpoint
                </button>
                <div class="m-2 progress" style="width: 30%">
                    <div id="energy" class="progress-bar progress-bar-striped progress-bar-animated" 
                    role="progressbar" style="width: 0%"></div>
                </div>
                <div class="m-2 progress" style="width: 30%">
                    <div id="exp" class="progress-bar progress-bar-striped progress-bar-animated bg-warning text-dark" 
                    role="progressbar" style="width: 0%"></div>
                </div>
            </div>
        </div>
        <div id="mapid"></div>
    </div>
    
    <!-- Modal -->
    <div class="modal fade" id="sendPost" tabindex="-1" role="dialog" 
    aria-labelledby="postTitle" aria-hidden="true">
        <div class="modal-dialog modal-dialog-centered" role="document">
        <div class="modal-content">
            <div class="modal-header">
            <h5 class="modal-title" id="postTitle">Post</h5>
            <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                <span aria-hidden="true">&times;</span>
            </button>
            </div>
            <div class="modal-body" >
            <form method="post" action="{{route('posts.store')}}">
                    @csrf
                    <input type="hidden" name="lat" id="modal_lat" value="">
                    <input type="hidden" name="lng" id="modal_lng" value="">
                    <div class="form-group">
                        <label class="col-form-label">Content:</label>
                        <textarea class="form-control" name="content" rows="10"></textarea>
                    </div>
                    <div class="form-group">
                        <label class="col-form-label">Link:</label>
                        <input type="text" class="form-control" name="link" placeholder="Optional">
                    </div>
                    <button type="submit" class="btn btn-dark col-sm-12">Submit</button>
                </form>
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-secondary" data-dismiss="modal">Close</button>
            </div>
        </div>
        </div>
    </div>
    <!-- Modal 2 -->
    <div class="modal fade" id="pinPoint" tabindex="-1" role="dialog" 
    aria-labelledby="pinTitle" aria-hidden="true">
        <div class="modal-dialog modal-dialog-centered" role="document">
        <div class="modal-content">
            <div class="modal-header">
            <h5 class="modal-title" id="pinTitle">Create a Pinpoint</h5>
            <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                <span aria-hidden="true">&times;</span>
            </button>
            </div>
            <div class="modal-body" >
            <form method="post" action="{{route('posts.store')}}">
                    @csrf
                    <!-- type="hidden" -->
                    <input type="text" name="lat" id="pp_lat" value="">
                    <input type="text" name="lng" id="pp_lng" value="">
                    <div class="form-group">
                        <label class="col-form-label">Content:</label>
                        <textarea class="form-control" name="content" rows="10"></textarea>
                    </div>
                    <div class="form-group">
                        <label class="col-form-label">Link:</label>
                        <input type="text" class="form-control" name="link" placeholder="Optional">
                    </div>
                    <button type="submit" class="btn btn-dark col-sm-12">Submit</button>
                </form>
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-secondary" data-dismiss="modal">Close</button>
            </div>
        </div>
        </div>
    </div>

    <script>
        geoLocationInit();
        //initialize location service
        function geoLocationInit() {
            if (navigator.geolocation) {
                navigator.geolocation.getCurrentPosition(success, fail, {timeout:1000});
            } else {
                alert("Browser not supported, or Https protocol needed. Try type https:// before the url");
            }
        }
        //success
        function success(position) {
            lat = position.coords.latitude;
            lng = position.coords.longitude;
            mylatlng = L.latLng(position.coords.latitude, position.coords.longitude);

            //update lat and lng in modal
            document.getElementById("modal_lat").value = lat;
            document.getElementById("modal_lng").value = lng;
        }
        //failed to get position
        function fail() {
            alert("Location Service is disabled");
        }
        
        //upvote
        function upvote(id) {
            $.post("/pages/upvote",{id:id},function(data){
                if (data.redirect) {
                    alert("No enough energy");
                    //window.location.href = data.redirect;
                } else {
                    $("#energy").html("ENERGY "+data[0]);
                    $("#energy").css({"width": data[0]/10 + "%"});
                    $("#exp").html("LEVEL "+ Math.floor(data[1]/100));
                    $("#exp").css({"width": data[1] % 100 + "%"});
                }  
                
            });
        }
        $(document).ready(function(){
            //instruct jQuery to automatically add the token to all request headers
            $.ajaxSetup({
                headers: {
                    'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
                }
            });

            var mymap = L.map('mapid');//.locate({setView: true, maxZoom: 17});
            if (postjson) {
                mymap.setView([postjson.coordinates[1], postjson.coordinates[0]], 17);
            } else {
                mymap.setView([lat, lng], 17);
            }
            
                       
            L.tileLayer('https://api.tiles.mapbox.com/v4/{id}/{z}/{x}/{y}.png?access_token={accessToken}', {
                attribution: 'Map data &copy; <a href="https://www.openstreetmap.org/">OpenStreetMap</a> contributors, <a href="https://creativecommons.org/licenses/by-sa/2.0/">CC-BY-SA</a>, Imagery ? <a href="https://www.mapbox.com/">Mapbox</a>',
                id: 'mapbox.streets', maxZoom: 22, 
                accessToken: 'pk.eyJ1IjoibWFwYm94IiwiYSI6ImNpejY4NXVycTA2emYycXBndHRqcmZ3N3gifQ.rJcFIG214AriISLbB6B5aw'
            }).addTo(mymap);
            
            //circle
            var circle = L.circle(mylatlng, {
                color: 'opaque',
                fillColor: 'black',
                fillOpacity: 0.1,
                radius: 100
            });
            circle.addTo(mymap);
            //cursor
            var popup = L.popup();
            function onMapClick(e) {
                popup
                    .setLatLng(e.latlng)
                    .setContent("You clicked the map at " + e.latlng.toString()
                        + "/ Distance:" + e.latlng.distanceTo(mylatlng))
                    .openOn(mymap);
                //update lat and lng in modal
                document.getElementById("pp_lat").value = e.latlng.lat;
                document.getElementById("pp_lng").value = e.latlng.lng;
            }
            mymap.on('click', onMapClick);

            //initialize exp, energy
            $("#energy").html("ENERGY {{$energy}}");
            $("#energy").css({"width": "{{$energy/10}}%"});
            $("#exp").html("LEVEL {{floor($exp/100)}}");
            $("#exp").css({"width": "{{$exp % 100}}%"});


            //initialize post markers
            $.post("/pages/loadposts",{lat:lat, lng:lng},function(markers){
                //console.log(markers);
                $.each(markers,function(i,val){
                    var m_location = L.latLng(val.location.coordinates[1], val.location.coordinates[0]);
                    var m_username ="<p class='my-0 text-primary font-weight-bold'>" + val.user_name + "</p>";
                    var m_radius = "<p class='my-0 text-success font-weight-bold'>Radius: " + val.radius + "</p>";
                    //var m_user_id = val.user_id;
                    //var m_radius = val.radius;
                    var html = document.createElement("div");
                    $(html).append(m_username);
                    if(val.content) {
                        var m_content = "<p class='my-0'>" + val.content + "</p>";
                        $(html).append(m_content);
                    }
                    var marker = new L.Marker(m_location);

                    if(val.link) {
                        var link = "<img class='rounded mx-auto d-block' href="+ val.link +"></img>";
                        $(html).append(link);
                        //icon
                    } else {
                        //icon
                    }
                    $(html).append(m_radius);
                    var button = "<button onClick=upvote("+ val.id +")>Upvote</button>";
                    $(html).append(button);
                    marker.bindPopup(html);
                    marker.addTo(mymap);
                });
            });

            //initialize pinpoint markers
            $.post("/pages/loadpinpoints",{lat:lat, lng:lng},function(markers){
                //console.log(markers);
                $.each(markers,function(i,val){
                    var m_location = L.latLng(val.location.coordinates[1], val.location.coordinates[0]);
                    var m_username ="<p class='my-0 text-primary font-weight-bold'>" + val.user_name + "</p>";
                    var m_radius = "<p class='my-0 text-success font-weight-bold'>Radius: " + val.radius + "</p>";
                    var html = document.createElement("div");
                    $(html).append(m_username);
                    if(val.content) {
                        var m_content = "<p class='my-0'>" + val.content + "</p>";
                        $(html).append(m_content);
                    }
                    var marker = new L.Marker(m_location);
                    if(val.link) {
                        var link = "<img class='rounded mx-auto d-block' href="+ val.link +"></img>";
                        $(html).append(link);
                        //icon
                    } else {
                        //icon
                    }
                    $(html).append(m_radius);
                    marker.bindPopup(html);
                    marker.addTo(mymap);
                });
            });
        })

    </script>
@endsection