var map;
var pErreur = document.getElementById('pErreur');

var nombreEspece = document.querySelector("#countEspece").textContent;
var countEspece = parseInt(nombreEspece);

espece          = document.querySelectorAll("#tableEspece td");
longitude       = document.querySelectorAll("#tableLong td");
latitude        = document.querySelectorAll("#tableLat td");
commentaire     = document.querySelectorAll("#tableComm td");
date            = document.querySelectorAll("#tableDate td");
photo           = document.querySelectorAll("#tablePhoto td");

var currentInfoWindow = null;

function initMap()
{
    var i;
    var uluru = {lat: 40.363, lng: -10.044};
    map = new google.maps.Map(document.getElementById('map'), {
        zoom: 2,
        center: uluru
    });

    for ( i = 0; i < espece.length ; i++)
    {
        longitudeFloat      = parseFloat(longitude[i].textContent);
        latitudeFloat       = parseFloat(latitude[i].textContent);
        especeString        = espece[i].textContent;
        commentaireString   = commentaire[i].textContent;
        dateString          = date[i].textContent;
        photoString         = photo[i].textContent;
        var maPhoto         = photoString.trim();

        ajouterMarqueur(latitudeFloat, longitudeFloat, especeString, commentaireString, dateString, maPhoto, i);
    }
}

function ajouterMarqueur (latitude, longitude, espece, commentaire, date, photo)
{
    var marker;
    var placement = {lat: latitude, lng: longitude};
        marker = new google.maps.Marker({
            position: placement,
            map: map
        });
        marker.setMap(map);

    var infowindow = new google.maps.InfoWindow();

    google.maps.event.addListener(marker, 'click', function() {
        if (currentInfoWindow != null) {
            currentInfoWindow.close();
        }
            infowindow.setContent(espece + "<br>" + date + "<br>" + commentaire + "<br>" + "<a href='../uploads/img/"+photo+"'>image</a> " );
            infowindow.open(map, marker);
        currentInfoWindow = infowindow;
    });
}