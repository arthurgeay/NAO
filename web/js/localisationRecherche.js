var map;
var marker;
espece      = document.querySelectorAll("#tableEspece td");
longitude   = document.querySelectorAll("#tableLong td");
latitude    = document.querySelectorAll("#tableLat td");
function initMap()
{
    var uluru = {lat: 40.363, lng: -10.044};
    map = new google.maps.Map(document.getElementById('map'), {
        zoom: 2,
        center: uluru
    });

    for (var i = 0; i < espece.length ; i++)
    {
        alert(espece[i].textContent);
        alert(longitude[i].textContent);
        alert(latitude[i].textContent);
       longitudeFloat = parseFloat(longitude[i].textContent);
        latitudeFloat = parseFloat(latitude[i].textContent);

        ajouterMarqueur(latitudeFloat, longitudeFloat);
    }
}

function ajouterMarqueur (latitude, longitude)
{
    var placement = {lat: latitude, lng: longitude};
        marker = new google.maps.Marker({
            position: placement,
            map: map
        });
        marker.setMap(map);
}