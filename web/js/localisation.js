function initMap() {
    // Affichage de la map
    var map = new google.maps.Map(document.getElementById('map'), {
        center: {
            lat: 46.227638,
            lng: 2.213749
        },
        zoom: 6
    });

    map.addListener('click', function(e) {
        placeMarkerAndPanTo(e.latLng, map);
    });

    //Géolocalisation
    var button = document.getElementById('geolocation');
    button.addEventListener('click', function() {
        // Try HTML5 geolocation.
        if (navigator.geolocation) {
            navigator.geolocation.getCurrentPosition(function(position) {
                var pos = {
                    lat: position.coords.latitude,
                    lng: position.coords.longitude
                };

                if (marker) {
                    marker.setPosition(pos);
                } else {
                    marker = new google.maps.Marker({
                        position: pos,
                        map: map
                    });
                    map.panTo(pos);
                }


                document.getElementById('omega_naobundle_observations_longitude').value = position.coords.longitude;
                document.getElementById('omega_naobundle_observations_latitude').value = position.coords.latitude;

            }, function() {
                handleLocationError(true, map, map.getCenter());
            });
        } else {
            // Browser doesn't support Geolocation
            handleLocationError(false, map, map.getCenter());
        }
    });
}

// Erreur de géolocalisation

function handleLocationError(browserHasGeolocation, map, pos) {
    var infoWindow = new google.maps.InfoWindow({
        map: map
    });
    infoWindow.setPosition(pos);
    infoWindow.setContent(browserHasGeolocation ?
        'Erreur: Le service de localisation a rencontré un problème.' :
        'Erreur: Votre navigateur ne supporte pas la géolocalisation.');
}

// Placement de marqueur pour coordonnées GPS

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