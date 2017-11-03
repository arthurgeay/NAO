function initMap() {
    var uluru = {lat: -25.363, lng: 131.044};
    var map = new google.maps.Map(document.getElementById('map'), {
        zoom: 4,
        center: uluru
    });
    var marker = new google.maps.Marker({
        position: uluru,
        map: map
    });

    map.addListener('click', function(e) {
        placeMarkerAndPanTo(e.latLng, map);
    });
}
var marker;

function placeMarkerAndPanTo(latLng, map) {
    if (marker) {
        marker.setPosition(latLng);
    } else {
        marker = new google.maps.Marker({
            position: latLng,
            map: map
        });
        map.panTo(latLng);
    }

    document.getElementById('omega_naobundle_observations_longitude').value = latLng.lng();
    document.getElementById('omega_naobundle_observations_latitude').value = latLng.lat();

}

function addMarkerWithBDD (latLng, map)
{
    //ajout des marqueurt de la bdd
}