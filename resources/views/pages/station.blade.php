@extends('layouts.app')

@section('content')
    <div id="mapframe">
        <div id="hoverbar" class="container pt-2 pl-0 pr-0">
            <div class="row col-sm-12 p-0 m-0">
                <!-- Button trigger modal -->
                <button type="button" class="btn btn-dark ml-2" data-toggle="modal" data-target="#sendStation">
                    Post
                </button>
                <button type="button" class="btn btn-dark ml-2" onclick=geoLocationInit()>
                    Locate
                </button>
                <button id="selectnode2" type="button" class="btn btn-dark ml-2 d-none" onclick=deselect()>Cancel Selection</button>
                <p id="mytag" class="h2 mb-0 ml-5"></p>
            </div>
        </div>
        <div id="mapid"></div>
    </div>
    
    <!-- Modal -->
    <div class="modal fade" id="sendStation" tabindex="-1" role="dialog" 
    aria-labelledby="postTitle" aria-hidden="true">
        <div class="modal-dialog modal-dialog-centered" role="document">
        <div class="modal-content">
            <div class="modal-header">
            <h5 class="modal-title" id="postTitle">Post a station!</h5>
            <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                <span aria-hidden="true">&times;</span>
            </button>
            </div>
            <div class="modal-body" >
            <div id="selectnode" class="row d-none">
                <p class="pl-3 m-1">This post will be connected with the selected station</p>
                <button type="button" class="btn btn-dark btn-sm" onclick=deselect()>Cancel Selection</button>
            </div>
            <form method="post" action="{{route('stations.store')}}">
                    @csrf
                    <input type="hidden" name="lat" id="pp_lat" value="">
                    <input type="hidden" name="lng" id="pp_lng" value="">
                    <input type="hidden" name="pointto" id="marker_id" value="">
                    <div class="form-group">
                        <label class="col-form-label">Describe this station:</label>
                        <textarea class="form-control" name="content" rows="5" placeholder="like: Sweet Dreams. closest to campus"></textarea>
                        <label class="col-form-label">You can even add image :D </label>
                        <input type="text" class="form-control" name="link" placeholder="Image url: (optional)">
                        <label class="col-form-label">Give your station series a tag:</label>
                        <input type="text" class="form-control" name="tag" placeholder="like: bubble tea shop">
                        <label class="col-form-label">Select the color and icon for your station:<input type="color" name="color" ></label>
                        <div class="form-group">
                            <div class="btn-group">
                                <input data-placement="bottomRight" name="icon" class="form-control icp icp-auto dropdown-toggle iconpicker-component" value="fas fa-bus"
                                        type="text" data-toggle="dropdown"/>
                                <span class="dropdown-menu"></span>
                                <span class="input-group-addon btn"></span>
                            </div>
                        </div>
                        
                    </div>
                    <button type="submit" class="btn btn-dark col-sm-12">Submit</button>
                </form>
            </div>
        </div>
        </div>
    </div>


    <script>
        //instruct jQuery to automatically add the token to all request headers
        $.ajaxSetup({
            headers: {
                'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
            }
        });
        $('.icp-auto').iconpicker({input: 'input,.iconpicker-input',});
        //initialize location service
        function geoLocationInit() {
            if (navigator.geolocation) {
                navigator.geolocation.getCurrentPosition(success, fail, {timeout:5000});
            } else {
                alert("Browser not supported");
            }
        }
        //success
        function success(position) {
            lat = position.coords.latitude;
            lng = position.coords.longitude;
            mylatlng = L.latLng(position.coords.latitude, position.coords.longitude);
            relocate(mylatlng);
        }
        //failed to get position
        function fail() {
            alert("Location Service is disabled");
        }

        function Node(id) {
            this.id = id;
            this.fromid = [];
            this.toid = null;
            this.color = null;
            this.marker = null;
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
            var tonode = getNode(node.toid);
            if (tonode.marker) {
                //add to_node
                tonode.marker.openPopup();
            }
            //add other nodes
            for(let id of node.fromid) {
                //use isPopupOpen()?
                getNode(id).marker.openPopup();
            }
        }
        function clickMarker(e) {
            var marker = e.target;
            //console.log(marker);
            proxyhighlight.setLatLng(marker.getLatLng());
            document.getElementById("marker_id").value = marker.id;
            document.getElementById("mytag").innerHTML = marker.tag;
            document.getElementById("selectnode").classList.remove('d-none');
            document.getElementById("selectnode2").classList.remove('d-none');
        }
        
        function deselect() {
            proxyhighlight.setLatLng([0,0]);
            document.getElementById("marker_id").value = null;
            document.getElementById("mytag").innerHTML = null;
            document.getElementById("selectnode").classList.add('d-none');
            document.getElementById("selectnode2").classList.add('d-none');
        }

        function relocate(mylatlng) {
            mymap.setView(mylatlng, 16);
        }

        var nodelist = [];
        var mymap = null;
        var proxyhighlight = null;
        var lat = null;
        var lng = null;
        var latlng = null;

        mymap = L.map('mapid', {
            zoomControl: false, 
            wheelPxPerZoomLevel: 120, 
            zoomSnap: 0.1, 
            worldCopyJump: true,
            minZoom: 1.5,
            maxBounds: L.latLngBounds(L.latLng(-85, -360), L.latLng(85, 360))    
        });
        mymap.setView([43.470502, -80.542862], 17);

        L.tileLayer('https://api.tiles.mapbox.com/v4/{id}/{z}/{x}/{y}.png?access_token={accessToken}', {
            attribution: 'Map data &copy; <a href="https://www.openstreetmap.org/">OpenStreetMap</a> contributors, <a href="https://creativecommons.org/licenses/by-sa/2.0/">CC-BY-SA</a>, Imagery ? <a href="https://www.mapbox.com/">Mapbox</a>',
            id: 'mapbox.streets', maxZoom: 22, 
            accessToken: 'pk.eyJ1IjoibWFwYm94IiwiYSI6ImNpejY4NXVycTA2emYycXBndHRqcmZ3N3gifQ.rJcFIG214AriISLbB6B5aw'
        }).addTo(mymap);
        

        //cursor
        let pulsingIcon = L.icon.pulse({iconSize:[10,10],color:'blue'});
        let mainmarker =new L.marker([0,0], {icon: pulsingIcon}).bindPopup().addTo(mymap);
        function onMapClick(e) {
            let clicklatlng = mymap.wrapLatLng(e.latlng);

            let html = document.createElement("div");
            let message = "<p class='m-0'>You clicked the map at " + clicklatlng.toString() + "</p></br>";
            let message2 = "<p class='m-0'>Here will be the location of your posted station</p></br>";
            let button = "<button type='button' class='btn btn-sm btn-dark ml-2' data-toggle='modal' data-target='#sendStation'>Post</button>"
            $(html).append(message);
            $(html).append(message2);
            $(html).append(button);
            mainmarker
                .setLatLng(clicklatlng)
                .setPopupContent(html);
            //update lat and lng in modal
            document.getElementById("pp_lat").value = clicklatlng.lat;
            document.getElementById("pp_lng").value = clicklatlng.lng;
        }
        mymap.on('click', onMapClick);
        
        let adminicon = L.icon({
            iconUrl: 'images/admintoken.png',
            iconSize:     [40, 40], 
            iconAnchor:   [18, 22], 
            popupAnchor:  [0, -18]
        });
        let smicon = L.icon({
            iconUrl: 'images/none.png',
            iconSize:     [1, 1], 
            iconAnchor:   [1, 1], 
        });
        let blackkicon = L.icon({
            iconUrl: 'images/blackselection.png',
            iconSize:     [100, 100], 
            iconAnchor:   [75, 75], 
        });
        proxyhighlight =new L.marker([0,0], {highlight: "permanent", icon: blackkicon}).addTo(mymap);
        
        //initialize station markers and lines
        $.post("/stations/load",{},function(markers){
            $.each(markers,function(i,val){

                let m_location = L.latLng(val.location.coordinates[1], val.location.coordinates[0]);
                let html = document.createElement("div");
                if(val.content) {
                    let m_content = "<p class='my-0'>" + val.content + "</p>";
                    $(html).append(m_content);
                }
                if(val.link) {
                    let link = "<img class='rounded mx-auto d-block img-responsive' style='max-width: 300px; height: auto' src="+ val.link +"></img>";
                    $(html).append(link);
                }
                let m_id =val.id;
                let node = getNode(m_id);
                node.color = val.color;
                if (val.pointto_id) {
                    node.toid = val.pointto_id;
                    getNode(val.pointto_id).addfromid(m_id);
                    //node.line = new L.Polyline.AntPath([], {delay: 700});
                } 

                let m_icon = val.icon;
                let pos = m_icon.indexOf(" ");
                m_icon = m_icon.slice(pos+4);
                let icon = L.BeautifyIcon.icon({
                    icon: m_icon,
                    iconShape: 'marker',
                    borderColor: val.color,
                    textColor: val.color,
                    popupAnchor: [0, -20]
                });
                node.marker = new L.Marker(m_location, {icon: icon});
                node.marker.id = m_id;
                node.marker.tag = val.category;
                node.marker.bindPopup(html, {autoPan: false, autoClose: false});
                node.marker.on({
                    popupopen: chainOpen,
                    click: clickMarker
                });
                node.marker.addTo(mymap);
            });
            console.log(nodelist);
            //add lines
            for(let node of nodelist) {
                if (node.toid) {
                    let node2 = getNode(node.toid);
                    let pt1 = node.marker.getLatLng();
                    let pt2 = node2.marker.getLatLng();
                    node.line = new L.Polyline.AntPath([pt1,pt2], {color: node.color, delay: 700});
                    node.line.addTo(mymap);
                    //node.line.options.delay = Math.min(150 + pt1.distanceTo(pt2)/2, 900);
                }
            }
        });
    </script>
@endsection