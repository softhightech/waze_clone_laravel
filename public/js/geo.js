
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


}
$().ready(function(){
    
    localisation();
    setInterval(localisation, 3000);
    $("#add").click(function(){
        $('#action').val('set');
    })
})
