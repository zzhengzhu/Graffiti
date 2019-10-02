<html>
<head>
  <meta charset="utf-8">
  <meta name="viewport" content="initial-scale=1, maximum-scale=1, user-scalable=no">
  <title>ArcGIS JavaScript Tutorials: Create a Starter App</title>
  <style>
    html, body, #viewDiv {
      padding: 0;
      margin: 0;
      height: 100%;
      width: 100%;
    }
  </style>
  
  <link rel="stylesheet" href="https://js.arcgis.com/4.12/esri/css/main.css">
  <script src="https://js.arcgis.com/4.12/"></script>
  
  <script>  
    require([
      "esri/Map",
      "esri/views/MapView",
      "esri/widgets/Locate",
      "esri/widgets/Track",
      "esri/Graphic",
      "esri/widgets/Compass",
      "esri/widgets/BasemapToggle",
      "esri/widgets/BasemapGallery", 
      "esri/layers/FeatureLayer", 
      "esri/layers/GraphicsLayer"
    ], function(Map, MapView, Locate, Track, Graphic, 
      Compass, BasemapToggle, BasemapGallery, FeatureLayer, GraphicsLayer) {

      var map = new Map({
        basemap: "streets-navigation-vector"
      });

      var view = new MapView({
        container: "viewDiv",
        map: map,
        center: [-118.80543,34.02700],
        zoom: 13
      });

      var track = new Track({
        view: view,
        graphic: new Graphic({
          symbol: {
            type: "simple-marker",
            size: "12px",
            color: "green",
            outline: {
              color: "#efefef",
              width: "1.5px"
            }
          }
        }),
        useHeadingEnabled: true  // Don't change orientation of the map
      });

      var compass = new Compass({
          view: view
        });

        var basemapGallery = new BasemapGallery({
        view: view,
        source: {
          portal: {
            url: "https://www.arcgis.com",
            useVectorBasemaps: false  // Load vector tile basemaps?
          }
        }
      });

       // Trailheads feature layer (points)
       var trailheadsLayer = new FeatureLayer({
        url: "https://services3.arcgis.com/GVgbJbqm8hXASVYi/arcgis/rest/services/Trailheads/FeatureServer/0"
      });

      var trailheadsRenderer = {
        type: "simple",
        symbol: {
          type: "picture-marker",
          url: "http://static.arcgis.com/images/Symbols/NPS/npsPictograph_0231b.png",
          width: "18px",
          height: "18px"
        }
      }

      var trailheadsLabels = {
        symbol: {
          type: "text",
          color: "#FFFFFF",
          haloColor: "#5E8D74",
          haloSize: "2px",
          font: {
            size: "12px",
            family: "Noto Sans",
            style: "italic",
            weight: "normal"
          }
        },
        labelPlacement: "above-center",
        labelExpressionInfo: {
          expression: "$feature.TRL_NAME"
        }
      };

      var trailheads = new FeatureLayer({
        url:
          "https://services3.arcgis.com/GVgbJbqm8hXASVYi/arcgis/rest/services/Trailheads/FeatureServer/0",
        //renderer: trailheadsRenderer,
        //labelingInfo: [trailheadsLabels]
      });

      //map.add(trailheads);

      function addGraphics(result) {
        graphicsLayer.removeAll();
        result.features.forEach(function(feature){
          var g = new Graphic({
            geometry: feature.geometry,
            attributes: feature.attributes,
            symbol: {
             type: "simple-marker",
              color: [0,0,0],
              outline: {
               width: 2,
               color: [0,255,255],
             },
              size: "20px"
            },
            popupTemplate: {
             title: "{TRL_NAME}",
             content: "This a {PARK_NAME} trail located in {CITY_JUR}."
            }
          });
          graphicsLayer.add(g);
        });
      }

      function queryFeatureLayer(point, distance, spatialRelationship, sqlExpression) {
        var query = {
          geometry: point,
          distance: distance,
          spatialRelationship: spatialRelationship,
          outFields: ["*"],
          returnGeometry: true,
          where: sqlExpression
        };
        trailheads.queryFeatures(query).then(function(result) {
          addGraphics(result, true);
        });
      }

      
      // Layer used to draw graphics returned
      var graphicsLayer = new GraphicsLayer();
      map.add(graphicsLayer);

      view.when(function(){
        queryFeatureLayer(view.center, 1500, "intersects");
      });

      view.on("click", function(event){
        queryFeatureLayer(event.mapPoint, 1500, "intersects");
      });

      var trailsLayer = new FeatureLayer({
        url: "https://services3.arcgis.com/GVgbJbqm8hXASVYi/arcgis/rest/services/Trails/FeatureServer/0",
      
        definitionExpression: "ELEV_GAIN < 250",

        //*** ADD ***//
        renderer: {
        type: "simple",
        symbol: {
            type: "simple-line",
            color: "green",
            width: "2px"
        }
        },

        //*** ADD ***//
        outFields: ["TRL_NAME","ELEV_GAIN"],

        //*** ADD ***//
        popupTemplate: {  // Enable a popup
        title: "{TRL_NAME}", // Show attribute value
        content: "The trail elevation gain is {ELEV_GAIN} ft."  // Display text in pop-up
        }
    });

      //map.add(trailsLayer, 0);

      // Parks and open spaces (polygons)
      var parksLayer = new FeatureLayer({
        url: "https://services3.arcgis.com/GVgbJbqm8hXASVYi/arcgis/rest/services/Parks_and_Open_Space/FeatureServer/0"
      });

      //map.add(parksLayer, 0);


      // adds the compass to the top left corner of the MapView
      view.ui.add(compass, "top-left");

      view.ui.add(track, "top-left");

      //view.ui.add(basemapGallery, "top-right");
    });
  </script>
</head>
<body>
  <div id="viewDiv"></div>
</body>
</html>