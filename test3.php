<html>
<head>
    <title>Google Map Demo</title>
    <meta name="viewport" content="initial-scale=1.0, user-scalable=no" />
    <link type="text/css" href="css/index.css" rel="stylesheet" />
    <script src="//ajax.googleapis.com/ajax/libs/jquery/1.8.3/jquery.min.js"></script>              <script type="text/javascript" src="http://maps.google.com/maps/api/js?sensor=false"></script>
    <script type="text/javascript" src="scripts/jquery.gmap3.js"></script>
    <script type="text/javascript" src="scripts/index.js"></script>
    
    <style type="text/css">
        h1
{
    font-size: 16px;
}

#map_canvas
{
    width: 800px;
    height: 500px;
    margin: 10px auto;
}

div#latlng-control
{
    background: #ffc;
    border: 1px solid #676767;
    padding: 2px 4px;
    position: absolute;
}

.line
{
    padding: 3px;
}
    </style>
    
    <script>
    
    var path = [
    { "lat": 43.00678, "lng": -89.53743 },
    { "lat": 43.00656, "lng": -89.53732 },
    { "lat": 43.005878, "lng": -89.53797 },
    { "lat": 43.005344, "lng": -89.53684 },
    { "lat": 43.003834, "lng": -89.535400 },
    { "lat": 43.003692, "lng": -89.533834 },
    { "lat": 43.006384, "lng": -89.533796 },
    { "lat": 43.0120328, "lng": -89.533667 },
    { "lat": 43.015931, "lng": -89.533635 },
    { "lat": 43.023030, "lng": -89.5335390 },
    { "lat": 43.032010, "lng": -89.533249 },
    { "lat": 43.040221, "lng": -89.5329596 },
    { "lat": 43.04632176, "lng": -89.5318224 },
    { "lat": 43.052562, "lng": -89.5277883 },
    { "lat": 43.060300, "lng": -89.52759526 },
    { "lat": 43.06401556, "lng": -89.5268978 },
    { "lat": 43.06681381, "lng": -89.5241620 },
    { "lat": 43.0714224, "lng": -89.52499888 },
    { "lat": 43.07468269, "lng": -89.52698371 },
    { "lat": 43.07490213, "lng": -89.53292749 },
    { "lat": 43.076203059, "lng": -89.53269145 },
    { "lat": 43.0765949, "lng": -89.5314576 },
    { "lat": 43.0793377, "lng": -89.53323862 },
    { "lat": 43.0803799, "lng": -89.53454754 },
    { "lat": 43.0835927, "lng": -89.5340754 },
    { "lat": 43.08458789, "lng": -89.5334853 },
    { "lat": 43.0844468, "lng": -89.53403256 },
    { "lat": 43.08445469, "lng": -89.5352985 },
    { "lat": 43.084619242, "lng": -89.5358993791 }
  ];

var poly1 = [
    { "lat": 43.0379081608, "lng": -89.451271661 },
    { "lat": 43.060613611, "lng": -89.45127166 },
    { "lat": 43.06086445, "lng": -89.4711843 },
    { "lat": 43.0454357, "lng": -89.47118438 }
];

var poly2 = [
    { "lat": 43.015194305, "lng": -89.455563195 },
    { "lat": 43.0154453329, "lng": -89.4252649627 },
    { "lat": 43.001197884, "lng": -89.42826903686 },
    { "lat": 43.001197884, "lng": -89.459425576 }
];

function createInfo(title, content) {
    return '<div id="popup"><h1 class="popup-title">' + title + '</h1><div id="popup-body"><p>' + content + '</p></div></div>';
}

$(document).ready(function () {
    // create the map
    var map = $("#map_canvas").gmap3(
    {
        lat: 43.0566,
        lng: -89.4511,
        zoom: 12
    });

    // turn on mouse hover debug helper
    map.toggleDebug();

    // add markers by address
    map.addMarkerByAddress("Madison, WI", "Madison", createInfo("Madison", "This point was added by geo-coding an address."));
    map.addMarkerByAddress("312 Monte Cristo Circle, Verona, WI", "Home2", createInfo("Madison", "This point was added by geo-coding an address."));

    // add markers by lat / long
    map.addMarkerByLatLng(43.0747, -89.3845, "State Capital", createInfo("State Capital", "This is the capital of the State of Wisconsin."));
    map.addMarkerByLatLng(43.0849, -89.5349, "Work", createInfo("TomoTherapy", "This is where I work."));
    map.addMarkerByLatLng(43.0068, -89.5376, "Home", createInfo("Home", "This is where I live."));

    // add click handlers
    map.onclickReverseGeocode($("#address"));
    map.onclickGetLatLng($("#latlng"));

    // add a path
    map.addPath(path);

    // add polygons
    map.addPolygon(poly1);
    map.addClickablePolygon(poly2, createInfo("Housing District", "Look at all the houses"),
    {
        fillColor: "#0000ff",
        strokeColor: "#0000ff"
    });

    // set up the button
    $("#addMarker").click(function () {
        var address = $("#addressToAdd").val();
        if (address != undefined && address != null && address != "") {
            $.fn.gmap3.geoCodeAddress(address, function (latlng) {
                $.fn.gmap3.geoCodeLatLng(latlng.lat(), latlng.lng(), function(foundAddress){
                    var str = "latitude = " + latlng.lat() + "\nlongitude = " + latlng.lng() + "\naddress = " + foundAddress;
                    alert(str);
                    map.addMarkerByLatLng(latlng.lat(), latlng.lng(), address, createInfo(address, str.replace(/\n/gi, "<br />")));
                });
            });
        }
    });
});

</script>
</head>
<body>
    <div id="wrapper">
        <div class="demo">
            <a href="http://blog.bobcravens.com/" class="demo">Bob <span>Cravens</span> Dotcom Demo Page</a>
        </div>
        <div id="content">
            <div id="map_canvas" class="line"></div>
            <div id="latlng" class="line">click the map</div>
            <div id="address" class="line">click the map</div>
            <div>
                Enter an address:
                <input id="addressToAdd" type="text" value="Vilas Zoo, Madison, WI" />
                <input id="addMarker" type="button" value="Add Marker" />
            </div>
        </div>
    </div>
</body>
</html>