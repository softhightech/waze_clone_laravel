<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta name="csrf-token" content="{{ csrf_token() }}" />
    <title>Ana Zima</title>
    <script src="https://code.jquery.com/jquery-3.7.1.min.js" integrity="sha256-/JqT3SQfawRcv/BIHPThkBvs0OEvtFFmqPF/lYI/Cxo=" crossorigin="anonymous"></script>

    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet" integrity="sha384-QWTKZyjpPEjISv5WaRU9OFeRpok6YctnYmDr5pNlyT2bRjXh0JMhjY6hW+ALEwIH" crossorigin="anonymous">
    <script src="js/geo.js"></script>
    <link rel="stylesheet" href="https://unpkg.com/leaflet@1.9.4/dist/leaflet.css"
     integrity="sha256-p4NxAoJBhIIN+hmNHrzRCf9tD/miZyoHS5obTRR9BMY="
     crossorigin=""/>
 <!-- Make sure you put this AFTER Leaflet's CSS -->
 <script src="https://unpkg.com/leaflet@1.9.4/dist/leaflet.js"
     integrity="sha256-20nQCchB9co0qIjJZRGuk2/Z9VM+kNiyxNV1lvTlZBo="
     crossorigin=""></script>

</head>
<body>
    <script>
        
function distance(lat1, lon1, lat2, lon2, unit) {
    if ((lat1 == lat2) && (lon1 == lon2)) {
        return 0;
    }
    else {
        var radlat1 = Math.PI * lat1/180;
        var radlat2 = Math.PI * lat2/180;
        var theta = lon1-lon2;
        var radtheta = Math.PI * theta/180;
        var dist = Math.sin(radlat1) * Math.sin(radlat2) + Math.cos(radlat1) * Math.cos(radlat2) * Math.cos(radtheta);
        if (dist > 1) {
            dist = 1;
        }
        dist = Math.acos(dist);
        dist = dist * 180/Math.PI;
        dist = dist * 60 * 1.1515;
        if (unit=="K") { dist = dist * 1.609344 }
        if (unit=="N") { dist = dist * 0.8684 }
        return dist;
    }
}

function geoFindMe() {
  const status = document.querySelector("#status");
  const mapLink = document.querySelector("#map-link");


    const long_input = document.querySelector("#longitude");
    const lat_input = document.querySelector("#latitude");


  mapLink.href = "";
  mapLink.textContent = "";

  function success(position) {
    // console.log(position.coords);
    // return false;
    const latitude = position.coords.latitude;
    const longitude = position.coords.longitude;
    lat_input.value = latitude;
    long_input.value = longitude;
    
    status.textContent = "";
    // mapLink.href = `https://www.openstreetmap.org/#map=18/${latitude}/${longitude}`;
    // mapLink.textContent = `Latitude: ${latitude} °, Longitude: ${longitude} °`;
  }

  

  function error() {
    status.textContent = "Unable to retrieve your location";
  }

  if (!navigator.geolocation) {
    status.textContent = "Geolocation is not supported by your browser";
  } else {
    // const options = {
    //     enableHighAccuracy: true,
    //     maximumAge: 0,
    //     timeout: 100
    // };
    status.textContent = "Locating…";
    navigator.geolocation.getCurrentPosition(success, error, {enableHighAccuracy: true,maximumAge: 300,timeout: 300});
  }
}
function localisation()
{
    geoFindMe();
    var aud = document.getElementById("ASong").children[0];
    var isPlaying = false;
    // aud.stop();
    $.ajaxSetup({
        headers: {
            'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
        }
    });
    let longitude = $('#longitude').val();
    let latitude = $('#latitude').val();
    let action = $('#action').val();
    if(longitude == '' || latitude == '')
    {
        return false;
    }
        $.ajax({
            url: 'https://dinmok.com/store',
            method: "POST",
            data: {"longitude":longitude,"latitude":latitude,"action": action},
            dataType: 'text',
            beforeSend:function() {
                // $("#save").attr('disabled', 'disabled');
            },
            success:function (data) {
                var data = JSON.parse(data);
                if(data.result == 'Kaynin police' )
                {
                    $('#kayen_plice').addClass('bg-danger');
                    $('#kayen_plice').html(data.result);
                    $('#action').val('get');
                    $('audio')[0].play();
                }else{
                    $('#kayen_plice').addClass('bg-success');
                    $('#kayen_plice').html(data.result);
                    $('#action').val('get');
                     $('audio')[0].stop();
                }
            },
            error:function (data) {
                // alert(data);
                // var data = JSON.parse(data);
                $('#kayen_plice').addClass('bg-info');
                // $('#kayen_plice').html(data.result);
                $('#action').val('get');
                $('.kayen_plice').append(data)
                // $("#save").attr('disabled', '');
            }
        })  

        
        let long = $('#longitude').val();
        let lat = $('#latitude').val();
        var map = L.map('map').setView([lat, long ], 18);
        
        
        //console.log('update map');
        //get last positions
        $.ajax({
            url: 'https://dinmok.com/store',
            method: "POST",
            data: {"longitude":longitude,"latitude":latitude,"action": 'nearbypoliceman'},
            dataType: 'text',
            beforeSend:function() {
                // $("#save").attr('disabled', 'disabled');
            },
            success:function (data) {
                var result = JSON.parse(data);
        
                $.each(result,function(index, value){
                    let long = value[index].longitude;
                    let lat = value[index].latitude;


                    var marker = L.marker([lat, long]).addTo(map);
                    L.tileLayer('https://tile.openstreetmap.org/{z}/{x}/{y}.png', {
                        maxZoom: 19,
                        attribution: '&copy; <a href="http://www.openstreetmap.org/copyright">OpenStreetMap</a>'
                    }).addTo(map);

                });
            },
            error:function (data) {
                // alert(data);
                // var data = JSON.parse(data);
                $('#kayen_plice').addClass('bg-info');
                // $('#kayen_plice').html(data.result);
                $('#action').val('get');
                $('.kayen_plice').append(data)
                // $("#save").attr('disabled', '');
            }
        })  


}
$().ready(function(){
    
    localisation();
    setInterval(localisation, 5000);
    $("#add").click(function(){
        $('#action').val('set');
    })
})

    </script>



<p id="status"></p>
<a id="map-link" target="_blank"></a>





<audio id="ASong"
    src="/siren-alert-96052.mp3"
    autoplay
    loop
  ></audio>

<form id="paramsForms" action="/" method="get" class="d-none">
    <input type="text" name="longitude" id="longitude" value="">
    <input type="text" name="latitude" id="latitude" value="">
    <input type="text" name="action" id="action" value="get">
    <button id="save">save</button>
</form>

<div class="container" class="">
    <div class="row" style="height:300px">
        <div id="left_controller" class="col-3">cl</div>
            <div id="kayen_plice"  class="col-6">No Police</div>
        <div id="right_controller" class="col-3"><button id="add">Kayen policier</button></div>
    </div>


</div>







     <div id="map"></div>


     <style>
        #map { height: 400px; }
     </style>
     <script>
 
     </script>

</body>
</html>
