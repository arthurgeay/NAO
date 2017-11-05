function initMap()
{
    var uluru = {lat: 40.363, lng: -10.044};
    var map = new google.maps.Map(document.getElementById('map'), {
        zoom: 2,
        center: uluru
    });
}

var marker;

function addMarkerTest ()
{
    var placement = {lat: 50.00215, lng: 10.2510}

    marker = new google.maps.Marker({
        position: placement,
        map: map
    });
    marker.setMap(map);
}

function addMarkerWithBDD (latitude, longitude, map)
{
    var placement = {lat: latitude, lng: longitude}

    marker = new google.maps.Marker({
        position: placement,
        map: map
    });
    marker.setMap(map);
}