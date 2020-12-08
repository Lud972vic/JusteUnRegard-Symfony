export function getDataPHP() {
    alert("je suis ici");

     let global;

    // document.addEventListener('load', function () {
    //     var userMaker = document.querySelector('.js-user-maker');
    //     var markers = userMaker.dataset.isAuthenticated;

    //     global = markers;
    // });
}

function lauchScriptLeaflet() {
 //   <script type="text/javascript" src="{{asset('js/leaflet.js')}}"></script>;
 //   <script type="text/javascript" src="{{asset('js/leaflet.markercluster.js')}}"></script>;
}

function pinOnMap() {
    var map = L.map('map', {
        center: [48.852969, 2.349903],
        minZoom: 5,
        zoom: 12
    });

    L.tileLayer('https://{s}.tile.openstreetmap.fr/osmfr/{z}/{x}/{y}.png').addTo(map);

    var myIcon = L.icon({
        iconUrl: '.\\uploads\\background\\instagram.png',
        iconSize: [64, 64],
        iconAnchor: [9, 21],
        popupAnchor: [0, -14]
    });

    var markerClusters = L.markerClusterGroup();

    for (var i = 0; i < markers.length; ++i) {
        var popup = markers[i].name;

        var m = L.marker([markers[i].lat, markers[i].lng], { icon: myIcon }).bindPopup(popup);
        markerClusters.addLayer(m);
    }

    map.addLayer(markerClusters);
}

function animation() {
    /*Animation avec la carte au chargement*/
    $(document).ready(function () {
        $("#map").hide();
        $("#map").fadeIn(5000);
    });
}
