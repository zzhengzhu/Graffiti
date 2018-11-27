@extends('layouts.app')

@section('content')
    <script>
        var urlParams = new URLSearchParams(window.location.search);
        postjson = JSON.parse(urlParams.get('postjson'));
        //console.log(postjson);
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
            <div id="selectnode" class="row d-none">
                <p class="pl-3 m-1">This post is point to a marker</p>
                <button type="button" class="btn btn-dark btn-sm" onclick=deselect()>Cancel Selection</button>
            </div>
            <form method="post" action="{{route('posts.store')}}">
                    @csrf
                    <input type="hidden" name="lat" id="modal_lat" value="">
                    <input type="hidden" name="lng" id="modal_lng" value="">
                    <input type="hidden" name="pointto" id="marker_id" value="">
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
            <form method="post" action="{{route('pinpoints.store')}}">
                    @csrf
                    <!-- type="hidden" -->
                    <input type="hidden" name="lat" id="pp_lat" value="">
                    <input type="hidden" name="lng" id="pp_lng" value="">
                    <div class="form-group">
                        <label class="col-form-label">Content:</label>
                        <textarea class="form-control" name="content" rows="7"></textarea>
                    </div>
                    <div class="form-group">
                        <label class="col-form-label">Link:</label>
                        <input type="text" class="form-control" name="link" placeholder="Optional">
                    </div>
                    <div class="form-group">
                        <label class="col-form-label">Radius:</label>
                        <input type="number" class="form-control" name="radius" placeholder="The radius of Pinpoint in meters (max:10000)" min="1" max="10000">
                    </div>
                    <button type="submit" class="btn btn-dark col-sm-12">Submit</button>
                </form>
            </div>
        </div>
        </div>
    </div>

    <script>
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
                    console.log(id);
                    console.log(data[2]);
                }  
                
            });
        }
        
        
        function Node(id) {
            this.id = id;
            this.fromid = [];
            this.toid = null;
            this.marker = null;
            this.line = null;
            this.circle = null;
            this.addfromid = function(id) {
                this.fromid.push(id);
                return null;
            }
            nodelist.push(this);
        }
        function getNode(id) {
            for(let node of nodelist) {
                //console.log("search node:" + id);
                //console.log(node.id);
                if (node.id == id) {
                    //console.log("matched!");
                    return node;
                }
            }
            //console.log("new node");
            return new Node(id);
        }
        function nodeExist(id) {
            for(node in nodelist) {
                if (node.id == id) {
                    return true;
                }
            }
            return false;
        }
        function chainOpen(e) {
            var node = getNode(e.target.id);
            node.circle.addTo(mymap);
            //console.log(node);
            //console.log(nodelist);
            var tonode = getNode(node.toid);
            if (tonode.marker) {
                //add line
                if (node.line.getLatLngs().length == 0) {
                    var pt1 = node.marker.getLatLng();
                    var pt2 = tonode.marker.getLatLng();
                    node.line.setLatLngs([pt1,pt2]);
                    node.line.options.delay = Math.min(150 + pt1.distanceTo(pt2)/2, 900);
                }
                node.line.addTo(mymap);
                //add to_node
                tonode.marker.openPopup();
            }
            //add other nodes
            for(let id of node.fromid) {
                //use isPopupOpen()?
                getNode(id).marker.openPopup();
            }
        }
        function chainClose(e) {
            var node = getNode(e.target.id);
            var tonode = getNode(node.toid);
            node.circle.remove(mymap);
            if (tonode.marker) {
                //close line
                if (node.line.getLatLngs().length == 0) {
                    var pt1 = node.marker.getLatLng();
                    var pt2 = tonode.marker.getLatLng();
                    node.line.setLatLngs([pt1,pt2]);
                    node.line.options.delay = Math.min(150 + pt1.distanceTo(pt2)/2, 900);
                }
                node.line.remove(mymap);
            }

            //closePopup()?
        }
        function clickMarker(e) {
            var marker = e.target;
            //console.log(marker);
            proxyhighlight.setLatLng(marker.getLatLng());
            document.getElementById("marker_id").value = marker.id;
            document.getElementById("selectnode").classList.remove('d-none');
        }
        
        function deselect() {
            proxyhighlight.setLatLng([0,0]);
            document.getElementById("marker_id").value = null;
            document.getElementById("selectnode").classList.add('d-none');
        }

        $(document).ready(function() {
            geoLocationInit();
            //make it global
            nodelist = [];
            //console.log( "document ready!" );
        });

        $(window).on("load", function(){
            //console.log( "window ready!" );
            //instruct jQuery to automatically add the token to all request headers
            $.ajaxSetup({
                headers: {
                    'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
                }
            });

            mymap = L.map('mapid', {wheelPxPerZoomLevel: 120});//.locate({setView: true, maxZoom: 17});
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
            
            /*
            var polyline = L.polyline([[43.468665, -80.544752], [43.470869, -80.545846]]);
            var decorator = L.polylineDecorator(polyline, {
                patterns: [
                    // defines a pattern of 10px-wide dashes, repeated every 20px on the line
                    {offset: 0, repeat: 20, symbol: L.Symbol.arrowHead({pixelSize: 10, polygon: false, pathOptions: {stroke: true}})}
                ]
            }).addTo(mymap);
            */

            //circle
            var circle = L.circle(mylatlng, {
                color: 'opaque',
                fillColor: 'black',
                fillOpacity: 0.1,
                radius: 100
            });
            circle.addTo(mymap);
            //cursor
            var pulsingIcon = L.icon.pulse({iconSize:[10,10],color:'blue'});
            var mainmarker =new L.marker([0,0], {icon: pulsingIcon}).bindPopup().addTo(mymap);
            function onMapClick(e) {
                mainmarker
                    .setLatLng(e.latlng)
                    .setPopupContent("You clicked the map at " + e.latlng.toString()
                        + "/ Distance:" + e.latlng.distanceTo(mylatlng));
                //update lat and lng in modal
                document.getElementById("pp_lat").value = e.latlng.lat;
                document.getElementById("pp_lng").value = e.latlng.lng;
            }
            mymap.on('click', onMapClick);
            
            var smicon = L.BeautifyIcon.icon({
                icon: 'location-arrow',
                iconShape: 'circle-dot',
                borderColor: 'transparent',
                popupAnchor: [0, 0]
            });
            proxyhighlight =new L.marker([0,0], {highlight: "permanent", icon: smicon}).addTo(mymap);

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
                    //var m_radius = "<p class='my-0 text-success font-weight-bold'>Radius: " + val.radius + "</p>";
                    //var m_user_id = val.user_id;
                    //var m_radius = val.radius;
                    var html = document.createElement("div");
                    $(html).append(m_username);
                    if(val.content) {
                        var m_content = "<p class='my-0'>" + val.content + "</p>";
                        $(html).append(m_content);
                    }
                    if(val.link) {
                        var link = "<img class='rounded mx-auto d-block img-responsive' style='max-width: 300px; height: auto' src="+ val.link +"></img>";
                        $(html).append(link);
                        //icon
                    } else {
                        //icon
                    }
                    //$(html).append(m_radius);
                    var button = "<button onClick=upvote("+ val.id +")>Upvote</button>";
                    $(html).append(button);

                    var m_id = "po" + val.id;
                    var node = getNode(m_id);
                    if (val.pointto_id) {
                        node.toid = val.pointto_id;
                        getNode(val.pointto_id).addfromid(m_id);
                        node.line = new L.Polyline.AntPath([], {delay: 700});
                    } 
                    
                    node.circle = new L.circle(m_location, {
                        color: 'opaque',
                        fillColor: 'green',
                        fillOpacity: 0.2,
                        radius: val.radius
                    });

                    var icon = L.BeautifyIcon.icon({
                        icon: 'location-arrow',
                        iconShape: 'marker',
                        borderColor: 'deepskyblue',
                        textColor: 'deepskyblue',
                        popupAnchor: [0, -20]
                    });
                    node.marker = new L.Marker(m_location, {highlight: 'temporary', icon: icon});
                    node.marker.id = m_id;
                    node.marker.bindPopup(html, {autoClose: false});
                    node.marker.on({
                        popupopen: chainOpen,
                        popupclose: chainClose,
                        click: clickMarker
                    });
                    //console.log(node);
                    //console.log(nodelist);
                    node.marker.addTo(mymap);
                    //node.marker.openPopup();
                });
            });

            //initialize pinpoint markers
            $.post("/pages/loadpinpoints",{lat:lat, lng:lng},function(markers){
                //console.log(markers);
                $.each(markers,function(i,val){
                    //console.log(val);
                    var m_location = L.latLng(val.location.coordinates[1], val.location.coordinates[0]);
                    var m_username ="<p class='my-0 text-primary font-weight-bold'>" + val.user_name + "</p>";
                    //var m_radius = "<p class='my-0 text-success font-weight-bold'>Radius: " + val.radius + "</p>";
                    var html = document.createElement("div");
                    $(html).append(m_username);
                    if(val.content) {
                        var m_content = "<p class='my-0'>" + val.content + "</p>";
                        $(html).append(m_content);
                    }
                    if(val.link) {  
                        var link = "<img class='rounded mx-auto d-block img-responsive' style='max-width: 300px; height: auto' src="+ val.link +"></img>";
                        $(html).append(link);
                        //icon
                    } else {
                        //icon
                    }
                    //$(html).append(m_radius);
                    
                    //retrieve existing node or create a new node
                    var m_id = "pp" + val.id;
                    var node = getNode(m_id);
                    
                    node.circle = new L.circle(m_location, {
                        color: 'opaque',
                        fillColor: 'green',
                        fillOpacity: 0.2,
                        radius: val.radius
                    });

                    //var pulsingIcon = L.icon.pulse({iconSize:[20,20],color:'red'});
                    var icon = L.BeautifyIcon.icon({
                        icon: 'map-pin',
                        iconShape: 'circle',
                        popupAnchor: [0, 0]
                    });
                    node.marker = new L.Marker(m_location, {highlight: 'temporary', icon: icon});
                    node.marker.id = m_id;
                    node.marker.bindPopup(html, {autoClose: false});
                    node.marker.on({
                        popupopen: chainOpen,
                        popupclose: chainClose,
                        click: clickMarker
                    });
                    //console.log(node);
                    //console.log(nodelist);
                    node.marker.addTo(mymap);
                    //node.marker.openPopup();
                });
            });
        });

    </script>
@endsection